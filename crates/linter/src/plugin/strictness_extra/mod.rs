use rules::no_elvis_operator::NoElvisOperator;
use rules::no_empty_construct::NoEmptyConstruct;
use rules::require_strict_behavior::RequireStrictBehavior;

use crate::definition::PluginDefinition;
use crate::plugin::Plugin;
use crate::rule::Rule;

pub mod rules;

#[derive(Debug)]
pub struct StrictnessExtraPlugin;

impl Plugin for StrictnessExtraPlugin {
    fn get_definition(&self) -> PluginDefinition {
        PluginDefinition {
            name: "Strictness Extra",
            description: "Provides extra rules that enforce strictness in the codebase.",
            enabled_by_default: false,
        }
    }

    fn get_rules(&self) -> Vec<Box<dyn Rule>> {
        vec![Box::new(NoElvisOperator), Box::new(NoEmptyConstruct), Box::new(RequireStrictBehavior)]
    }
}
