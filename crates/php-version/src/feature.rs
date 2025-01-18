#[derive(Debug, Clone, Copy, PartialEq, Eq, Hash)]
pub enum Feature {
    NullCoalesceAssign,
    ParameterContravariance,
    ReturnCovariance,
    PregUnmatchedAsNull,
    NonCapturingCatches,
    NativeUnionTypes,
    RequiredParameterAfterOptional,
    LessOverridenParametersWithVariadic,
    ThrowExpression,
    ClassConstantOnExpression,
    PromotedProperties,
    NamedArguments,
    ThrowsTypeErrorForInternalFunctions,
    ThrowsValueErrorForInternalFunctions,
    HHPrintfSpecifier,
    StricterRoundFunctions,
    ThrowsOnInvalidMbStringEncoding,
    WarnsAboutFinalPrivateMethods,
    CallableInstanceMethods,
    LegacyConstructor,
    UnsetCast,
    CaseInsensitiveConstantNames,
    InterfaceConstantImplicitlyFinal,
    ArrayFunctionsReturnNullWithNonArray,
    SubstrReturnFalseInsteadOfEmptyString,
    CurlUrlOptionCheckingFileSchemeWithOpenBasedir,
    EmptyStringValidAliasForNoneInMbSubstituteCharacter,
    NumericStringValidArgInMbSubstituteCharacter,
    ParameterTypeWidening,
    AllUnicodeScalarCodePointsInMbSubstituteCharacter,
    PassNoneEncodings,
    RequiredParameterAfterOptionalNullableAndDefaultNull,
    FinalConstants,
    ReadonlyProperties,
    Enums,
    PureIntersectionTypes,
    TentativeReturnTypes,
    ClosureCreation,
    ArrayUnpackingWithStringKeys,
    SerializableRequiresMagicMethods,
    DynamicProperties,
    StrSplitReturnsEmptyArray,
    DisjunctiveNormalForm,
    ReadonlyClasses,
    NeverReturnTypeInArrowFunction,
    PregCaptureOnlyNamedGroups,
    ConstantsInTraits,
    AsymmetricVisibility,
    LazyObjects,
    RequiredParameterAfterOptionalUnionOrMixed,
    DateTimeExceptions,
    TypedClassLikeConstants,
    OverrideAttribute,
    DynamicClassConstantAccess,
    ReadonlyAnonymousClasses,
    CastsNumbersToStringsOnLooseComparison,
    NonNumericStringAndIntegerIsFalseOnLooseComparison,
    AbstractTraitMethods,
    ImplicitlyNullableParameterTypes,
    HighlightStringDoesNotReturnFalse,
    PropertyHooks,
    JsonValidate,
    ClosureInConstantExpressions,
}
