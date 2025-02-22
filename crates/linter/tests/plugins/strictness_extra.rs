use mago_linter::plugin::strictness_extra::rules::no_empty_construct::NoEmptyConstruct;
use mago_linter::plugin::strictness_extra::rules::require_strict_behavior::RequireStrictBehavior;

use crate::rule_test;

rule_test!(test_no_empty, NoEmptyConstruct);
rule_test!(test_require_strict_behavior, RequireStrictBehavior);
