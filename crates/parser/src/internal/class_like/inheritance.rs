use mago_ast::ast::*;
use mago_ast::sequence::TokenSeparatedSequence;
use mago_token::T;

use crate::error::ParseError;
use crate::internal::identifier::parse_identifier;
use crate::internal::token_stream::TokenStream;
use crate::internal::utils;

pub fn parse_optional_implements(stream: &mut TokenStream<'_, '_>) -> Result<Option<Implements>, ParseError> {
    Ok(match utils::maybe_peek(stream)?.map(|t| t.kind) {
        Some(T!["implements"]) => Some(Implements {
            implements: utils::expect_any_keyword(stream)?,
            types: {
                let mut types = Vec::new();
                let mut commas = Vec::new();
                loop {
                    types.push(parse_identifier(stream)?);

                    match utils::maybe_peek(stream)?.map(|t| t.kind) {
                        Some(T![","]) => {
                            commas.push(utils::expect_any(stream)?);
                        }
                        _ => break,
                    }
                }

                TokenSeparatedSequence::new(types, commas)
            },
        }),
        _ => None,
    })
}

pub fn parse_optional_extends(stream: &mut TokenStream<'_, '_>) -> Result<Option<Extends>, ParseError> {
    Ok(match utils::maybe_peek(stream)?.map(|t| t.kind) {
        Some(T!["extends"]) => Some(Extends {
            extends: utils::expect_any_keyword(stream)?,
            types: {
                let mut types = Vec::new();
                let mut commas = Vec::new();
                loop {
                    types.push(parse_identifier(stream)?);

                    match utils::maybe_peek(stream)?.map(|t| t.kind) {
                        Some(T![","]) => {
                            commas.push(utils::expect_any(stream)?);
                        }
                        _ => break,
                    }
                }
                TokenSeparatedSequence::new(types, commas)
            },
        }),
        _ => None,
    })
}
