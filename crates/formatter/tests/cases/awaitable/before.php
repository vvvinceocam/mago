<?php

declare(
strict_types


=1
);

namespace
Psl\Async;

use 
Psl\Promise\PromiseInterface; use Override;
use 
Psl\Async\Internal\AwaitableIterator;
use 
Revolt\EventLoop;
use 
Closure;
use 
Throwable;
use 
Generator;
use 
function is_array;
use 
Psl\Async\Internal\State;
/**
 The following class was derived from code of Amphp.

 https://github.com/amphp/amp/blob/ac89b9e2ee04228e064e424056a08590b0cdc7b3/lib/Future.php

 Code subject to the MIT license (https://github.com/amphp/amp/blob/ac89b9e2ee04228e064e424056a08590b0cdc7b3/LICENSE).

 Copyright (c) 2015-2021 Amphp ( https://amphp.org )

 @template T

 @implements PromiseInterface<T>

 @mago-ignore best-practices/no-else-clause
 */
readonly final

class Awaitable 
implements
PromiseInterface
{
    private
    State $state
    ;

    /**
 @param State<T> $state

 @internal Use {@see Deferred} to create and resolve an awaitable.
     */
    public function __construct(
    State $state) { $this->state 
        
        = $state; }

    /**
     * Iterate over the given `Awaitable`s in completion order.
     *
      @template Tk
      @template Tv
     
     * @param iterable<Tk, Awaitable<Tv>> $awaitables
     *
     * @return Generator<Tk, Awaitable<Tv>, null, void>
     */
    static 
    
    public
  
    function iterate(
    iterable $awaitables
    ): Generator {
        $iterator = new AwaitableIterator();

        if (is_array($awaitables)
        ) {
           
            foreach ($awaitables as $key => $awaitable) {
                
                /** @psalm-suppress MissingThrowsDocblock */
                $iterator->enqueue($awaitable->state, $key, $awaitable);
        }

      /** @psalm-suppress MissingThrowsDocblock */
      $iterator->complete();
    } else {
      EventLoop::defer(static function() use(
      $awaitables,
      $iterator
      ):void{
      // @codeCoverageIgnoreStart
      try {
          foreach (
          
          $awaitables as
          $key => $awaitable
          
          ) { $iterator->enqueue($awaitable->state,
              $key,
              $awaitable,
              )
        
              ;
        
          }

        
          $iterator->complete();
          } catch (Throwable $throwable) { $iterator->error($throwable); }
          // @codeCoverageIgnoreEnd
      });
        }

        /** @psalm-suppress MissingThrowsDocblock */
        do {$item = $iterator->consume(); if (!$item) { break; }

      yield $item[0] => $item[1];
        } while (true);
    }

    /**
     * @template Tv
     *
     * @param Tv $result
     *
     * @return Awaitable<Tv>
     */
    static public  function complete(
    mixed $result
    ): self
    {
           /** @var State<Tv> $state */
           $state = new State();
           /** @psalm-suppress MissingThrowsDocblock */
           $state->complete(
           $result
           );

           return new
           self($state);
    }

    /**
        * @return Awaitable<void>
        */
     static public function error(
        Throwable $throwable
        ): self
    {
           /** @var State<void> $state */
           $state = new State();
           /** @psalm-suppress MissingThrowsDocblock */
           $state->error($throwable);

           return new self($state);
    }

    /**
        * @return bool True if the operation has completed.
        *
        * @psalm-mutation-free
        */
    public
    function isComplete(
    ): bool
    {
           return $this->state->isComplete();
    }

    /**
        * {@inheritDoc}
        *
        * @template Ts
        *
        * @param Closure(T): Ts $success
        * @param Closure(Throwable): Ts $failure
        *
        * @return Awaitable<Ts>
        */
    #[
    Override
,]
    public function then(Closure $success, Closure $failure): Awaitable
    {
           /** @var State<Ts> $state */
           $state = new State();  $this->state->subscribe(
           /**
           * @param null|Throwable $error
           * @param null|T $value
           */
           static function (null|Throwable $error, mixed $value) use ($state, $success, $failure): void {
         if ($error) {
            
             try {
            
                 $state->complete($failure($error));
            
             } catch (Throwable $throwable) {
            
                 $state->error($throwable);
            
             }

            
             return;
         }

         try {
            
/**
             * @var T $value
             */
             $state->complete($success($value));
         } catch (Throwable $throwable) {
            
             $state->error(
             $throwable
             );
         }
           },
           );

           return new self(
           $state
           );
    }

    /**
        * {@inheritDoc}
        *
        * @template Ts
        *
        * @param Closure(T): Ts $success
        *
        * @return Awaitable<Ts>
        */
    #[Override]
    public function map(Closure $success): Awaitable
    {
       return $this
       ->
       then(
       $success,
       static fn(
       Throwable $throwable):never=>throw $throwable,
       );
    }

    /**
    * {@inheritDoc}
    *
    * @template Ts
    *
    * @param Closure(Throwable): Ts $failure
        *
        * @return Awaitable<T|Ts>
        */
    #[Override
    
    ]
    public function catch(Closure $failure): Awaitable
    {
           return $this->then(
           /**
           * @param T $value
           *
           * @return T
           */
           static function (
            mixed
           
           
            $value): mixed {
          return $value;
      },
      $failure,
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param Closure(): void $always
     *
     * @return Awaitable<T>
     */
    #[
    Override,
    ]
    public 
    function 
        always
        (Closure $always
        
        
        ): Awaitable
    {
        /** @var State<T> $state */
        $state = new State(); $this->state->subscribe(static function (null|Throwable $error, mixed $value) use ($state, $always): void {
       try {
           $always();

           if ($error) {
            
               $state->error($error);
           } else {
            
               /**
                    * @var T $value
                        */
               $state->complete($value);
           }
       } catch (Throwable $throwable) {
           $state->error($throwable);
       }
        });

        return new self($state);
    }

    /**
     * Awaits the operation to complete.
     *
     * Throws a `Throwable` if the operation fails.
     *
     * @return T
     */
    public function await(): mixed
    {
        $suspension = EventLoop::getSuspension();

        $this->state->subscribe(
       /**
       * @param null|Throwable $error
       * @param null|T $value
       */
       static function (null|Throwable $error, mixed $value) use ($suspension): void {
           if ($error) { $suspension->throw(
               
               
               $error
               
               
               
               );
           } else { $suspension->resume(
               
               
               $value
               
               
               
               );
      
           }
      
       },
        );

        
        
        /** @var T */
        return $suspension->suspend(
        
        
        
        
        
        
        );
    }

    /**
     * Do not forward unhandled errors to the event loop handler.
     */
    public
    function
    ignore(
    ): self { $this->state->ignore(); 

        return$this;         }
}


















?>


