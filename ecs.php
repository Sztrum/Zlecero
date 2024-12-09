<?php

use PhpCsFixer\Fixer\Alias\ArrayPushFixer;
use PhpCsFixer\Fixer\Alias\BacktickToShellExecFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoMultilineWhitespaceAroundDoubleArrowFixer;
use PhpCsFixer\Fixer\ArrayNotation\NormalizeIndexBraceFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer;
use PhpCsFixer\Fixer\ArrayNotation\TrimArraySpacesFixer;
use PhpCsFixer\Fixer\ArrayNotation\WhitespaceAfterCommaInArrayFixer;
use PhpCsFixer\Fixer\Basic\BracesFixer;
use PhpCsFixer\Fixer\Basic\CurlyBracesPositionFixer;
use PhpCsFixer\Fixer\Basic\EncodingFixer;
use PhpCsFixer\Fixer\Casing\ClassReferenceNameCasingFixer;
	use PhpCsFixer\Fixer\Casing\ConstantCaseFixer;
	use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
	use PhpCsFixer\Fixer\ClassUsage\DateTimeImmutableFixer;
	use PhpCsFixer\Fixer\Comment\CommentToPhpdocFixer;
	use PhpCsFixer\Fixer\ControlStructure\ControlStructureBracesFixer;
	use PhpCsFixer\Fixer\ControlStructure\ControlStructureContinuationPositionFixer;
	use PhpCsFixer\Fixer\FunctionNotation\CombineNestedDirnameFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Import\SingleImportPerStatementFixer;
use PhpCsFixer\Fixer\Import\SingleLineAfterImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveIssetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveUnsetsFixer;
	use PhpCsFixer\Fixer\LanguageConstruct\DeclareEqualNormalizeFixer;
	use PhpCsFixer\Fixer\LanguageConstruct\DeclareParenthesesFixer;
use PhpCsFixer\Fixer\LanguageConstruct\ExplicitIndirectVariableFixer;
use PhpCsFixer\Fixer\LanguageConstruct\NullableTypeDeclarationFixer;
use PhpCsFixer\Fixer\LanguageConstruct\SingleSpaceAroundConstructFixer;
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use PhpCsFixer\Fixer\NamespaceNotation\BlankLineAfterNamespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\BlankLinesBeforeNamespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\CleanNamespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\NoLeadingNamespaceWhitespaceFixer;
use PhpCsFixer\Fixer\Naming\NoHomoglyphNamesFixer;
use PhpCsFixer\Fixer\Operator\AssignNullCoalescingToCoalesceEqualFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\LogicalOperatorsFixer;
use PhpCsFixer\Fixer\Operator\LongToShorthandOperatorFixer;
use PhpCsFixer\Fixer\Operator\NewWithParenthesesFixer;
use PhpCsFixer\Fixer\Operator\NoSpaceAroundDoubleColonFixer;
use PhpCsFixer\Fixer\Operator\NoUselessConcatOperatorFixer;
use PhpCsFixer\Fixer\Operator\NoUselessNullsafeOperatorFixer;
use PhpCsFixer\Fixer\Operator\ObjectOperatorWithoutWhitespaceFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Operator\StandardizeIncrementFixer;
use PhpCsFixer\Fixer\Operator\StandardizeNotEqualsFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\TernaryToNullCoalescingFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\AlignMultilineCommentFixer;
use PhpCsFixer\Fixer\Phpdoc\NoBlankLinesAfterPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\NoEmptyPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAddMissingParamAnnotationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAnnotationWithoutDotFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocIndentFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoAliasTagFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocReturnSelfReferenceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocScalarFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimConsecutiveBlankLineSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\PhpTag\EchoTagSyntaxFixer;
use PhpCsFixer\Fixer\PhpTag\FullOpeningTagFixer;
use PhpCsFixer\Fixer\PhpTag\LinebreakAfterOpeningTagFixer;
use PhpCsFixer\Fixer\PhpTag\NoClosingTagFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\ReturnNotation\ReturnAssignmentFixer;
use PhpCsFixer\Fixer\Semicolon\MultilineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Semicolon\NoEmptyStatementFixer;
use PhpCsFixer\Fixer\Semicolon\NoSinglelineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Semicolon\SemicolonAfterInstructionFixer;
use PhpCsFixer\Fixer\Semicolon\SpaceAfterSemicolonFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\StringNotation\ExplicitStringVariableFixer;
use PhpCsFixer\Fixer\StringNotation\SimpleToComplexStringVariableFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\ArrayIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBetweenImportGroupsFixer;
use PhpCsFixer\Fixer\Whitespace\CompactNullableTypeDeclarationFixer;
use PhpCsFixer\Fixer\Whitespace\CompactNullableTypehintFixer;
use PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

/**
 * https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/index.rst
 * https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/list.rst
 */
return static function (ECSConfig $ecsConfig): void {
	/**
	 * Sets
	 */
	$ecsConfig->sets([SetList::PSR_12]);

	/**
	 * Rules
	 */

	$ecsConfig->rules([
		ArrayPushFixer::class, // Converts simple usages of array_push($x, $y); to $x[] = $y;.
		ArraySyntaxFixer::class, // PHP arrays should be declared using the configured syntax. https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/array_notation/array_syntax.rst
		FullyQualifiedStrictTypesFixer::class, // Transforms imported FQCN parameters and return types in function arguments to short version. https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/import/fully_qualified_strict_types.rst
		GlobalNamespaceImportFixer::class, // Imports or fully qualifies global classes/functions/constants. https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/import/global_namespace_import.rst
		NoLeadingImportSlashFixer::class, // Remove leading slashes in use clauses.
		NoUnusedImportsFixer::class, // Unused use statements must be removed.
		OrderedImportsFixer::class, // Ordering use statements.
		SingleLineAfterImportsFixer::class, // Each namespace use MUST go on its own line and there MUST be one blank line after the use statements block.
		SingleImportPerStatementFixer::class, // There MUST be one use keyword per declaration.
		NoMultilineWhitespaceAroundDoubleArrowFixer::class, // Operator => should not be surrounded by multi-line whitespaces.
		NoWhitespaceBeforeCommaInArrayFixer::class, // In array declaration, there MUST NOT be a whitespace before each comma.
		NormalizeIndexBraceFixer::class, // Array index should always be written by using square braces.
		TrimArraySpacesFixer::class, // Arrays should be formatted like function/method arguments, without leading or trailing single line space.
		WhitespaceAfterCommaInArrayFixer::class, // In array declaration, there MUST be a whitespace after each comma.
		AlignMultilineCommentFixer::class, // Each line of multi-line DocComments must have an asterisk [PSR-5] and must be aligned with the first one.
		ArrayIndentationFixer::class, // Each element of an array must be indented exactly once.
		AssignNullCoalescingToCoalesceEqualFixer::class, // Use the null coalescing assignment operator ??= where possible.
		BlankLineAfterNamespaceFixer::class, // There MUST be one blank line after the namespace declaration.
		BlankLineBeforeStatementFixer::class, // An empty line feed must precede any configured statement.
		BlankLineBetweenImportGroupsFixer::class, // Putting blank lines between use statement groups.
		CastSpacesFixer::class, // A single space or none should be between cast and variable.
		BracesFixer::class, // The body of each structure MUST be enclosed by braces. Braces should be properly placed. Body of braces should be properly indented.
		CurlyBracesPositionFixer::class, // Curly braces must be placed as configured.
		EncodingFixer::class, // PHP code MUST use only UTF-8 without BOM (remove BOM).
		ClassDefinitionFixer::class, // Whitespace around the keywords of a class, trait, enum or interfaces definition should be one space.
		ClassReferenceNameCasingFixer::class, // When referencing an internal class it must be written using the correct casing.
		CleanNamespaceFixer::class, // Namespace must not contain spacing, comments or PHPDoc.
		CombineConsecutiveIssetsFixer::class, // Using isset($var) && multiple times should be done in one call.
		CombineConsecutiveUnsetsFixer::class, // Calling unset on multiple items should be done in one call.
		CombineNestedDirnameFixer::class, // Replace multiple nested calls of dirname by only one call with second $level parameter. Requires PHP >= 7.0.
		CommentToPhpdocFixer::class, // Comments with annotation should be docblock when used on structural elements.
		CompactNullableTypehintFixer::class, // Remove extra spaces in a nullable typehint.
		ConstantCaseFixer::class, // The PHP constants true, false, and null MUST be written using the correct casing.
		ControlStructureBracesFixer::class, // The body of each control structure MUST be enclosed within braces.
		ControlStructureContinuationPositionFixer::class, // Control structure continuation keyword must be on the configured line.
		DateTimeImmutableFixer::class, // Class DateTimeImmutable should be used instead of DateTime.
		DeclareEqualNormalizeFixer::class, // Equal sign in declare statement should be surrounded by spaces or not following configuration.
		DeclareParenthesesFixer::class, // There must not be spaces around declare statement parentheses.
        ExplicitIndirectVariableFixer::class, // Add curly braces to indirect variables to make them clear to understand. Requires PHP >= 7.0.
        NullableTypeDeclarationFixer::class, // Nullable single type declaration should be standardised using configured syntax.
        ListSyntaxFixer::class, // List (array destructuring) assignment should be declared using the configured syntax. Requires PHP >= 7.1.
        BlankLinesBeforeNamespaceFixer::class, // Controls blank lines before a namespace declaration.
        NoLeadingNamespaceWhitespaceFixer::class, // The namespace declaration line shouldn't contain leading whitespace.
        NoHomoglyphNamesFixer::class, // Replace accidental usage of homoglyphs (non ascii characters) in names.
        BinaryOperatorSpacesFixer::class, // Binary operators should be surrounded by space as configured.
        LogicalOperatorsFixer::class, // Use && and || logical operators instead of and and or.
        LongToShorthandOperatorFixer::class, // Shorthand notation for operators should be used if possible.
        NewWithParenthesesFixer::class, // All instances created with new keyword must (not) be followed by parentheses.
        NoSpaceAroundDoubleColonFixer::class, // There must be no space around double colons (also called Scope Resolution Operator or Paamayim Nekudotayim).
        NoUselessConcatOperatorFixer::class, // There should not be useless concat operations.
        NoUselessNullsafeOperatorFixer::class, // There should not be useless Null-safe operator ?-> used.
        ObjectOperatorWithoutWhitespaceFixer::class, // There should not be space before or after object operators -> and ?->.
        OperatorLinebreakFixer::class, // Operators - when multiline - must always be at the beginning or at the end of the line.
        StandardizeIncrementFixer::class, // Increment and decrement operators should be used if possible.
        StandardizeNotEqualsFixer::class, // Replace all <> with !=.
        TernaryOperatorSpacesFixer::class, // Standardize spaces around ternary operator.
        TernaryToNullCoalescingFixer::class, // Use null coalescing operator ?? where possible. Requires PHP >= 7.0.
        UnaryOperatorSpacesFixer::class, // Unary operators should be placed adjacent to their operands.
        BlankLineAfterOpeningTagFixer::class, // Ensure there is no code on the same line as the PHP open tag and it is followed by a blank line.
        EchoTagSyntaxFixer::class, // Replaces short-echo <?= with long format <?php echo/<?php print syntax, or vice-versa.
        FullOpeningTagFixer::class, // PHP code must use the long <?php tags or short-echo <?= tags and not other tag variations.
        LinebreakAfterOpeningTagFixer::class, // Ensure there is no code on the same line as the PHP open tag.
        NoClosingTagFixer::class, // The closing tag MUST be omitted from files containing only PHP.
        NoBlankLinesAfterPhpdocFixer::class, // There should not be blank lines between docblock and the documented element.
        NoEmptyPhpdocFixer::class, // There should not be empty PHPDoc blocks.
        PhpdocAddMissingParamAnnotationFixer::class, // PHPDoc should contain @param for all params.
        PhpdocAlignFixer::class, // All items of the given PHPDoc tags must be either left-aligned or (by default) aligned vertically.
        PhpdocAnnotationWithoutDotFixer::class, // PHPDoc annotation descriptions should not be a sentence.
        PhpdocIndentFixer::class, // Docblocks should have the same indentation as the documented subject.
        PhpdocLineSpanFixer::class, // Changes doc blocks from single to multi line, or reversed. Works for class constants, properties and methods only.
        PhpdocNoAliasTagFixer::class, // No alias PHPDoc tags should be used.
        PhpdocOrderFixer::class, // Annotations in PHPDoc should be ordered in defined sequence.
        PhpdocReturnSelfReferenceFixer::class, // The type of @return annotations of methods returning a reference to itself must the configured one.
        PhpdocScalarFixer::class, // Scalar types should always be written in the same form. int not integer, bool not boolean, float not real or double.
        PhpdocTrimConsecutiveBlankLineSeparationFixer::class, // Removes extra blank lines after summary and after description in PHPDoc.
        PhpdocTrimFixer::class, // PHPDoc should start and end with content, excluding the very first and last line of the docblocks.
        NoUselessReturnFixer::class, // There should not be an empty return statement at the end of a function.
        ReturnAssignmentFixer::class, // Local, dynamic and directly referenced variables should not be assigned and directly returned by a function or method.
        MultilineWhitespaceBeforeSemicolonsFixer::class, // Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new line for chained calls.
        NoEmptyStatementFixer::class, // Remove useless (semicolon) statements.
        NoSinglelineWhitespaceBeforeSemicolonsFixer::class, // Single-line whitespace before closing semicolon are prohibited.
        SemicolonAfterInstructionFixer::class, // Instructions must be terminated with a semicolon.
        SpaceAfterSemicolonFixer::class, // Fix whitespace after a semicolon.
        DeclareStrictTypesFixer::class, // Force strict types declaration in all files. Requires PHP >= 7.0.
        ExplicitStringVariableFixer::class, // Converts implicit variables into explicit ones in double-quoted strings or heredoc syntax.
        SimpleToComplexStringVariableFixer::class, // Converts explicit variables in double-quoted strings and heredoc syntax from simple to complex format (${ to {$).
        SingleQuoteFixer::class, // Convert double quotes to single quotes for simple strings.
        CompactNullableTypeDeclarationFixer::class, // Remove extra spaces in a nullable type declaration.
        IndentationTypeFixer::class, // Code MUST use configured indentation type.
    ]);

	$ecsConfig->ruleWithConfiguration(ConcatSpaceFixer::class, [
		'spacing' => 'one',
	]);

    $ecsConfig->ruleWithConfiguration(SingleSpaceAroundConstructFixer::class, [
        'constructs_followed_by_a_single_space' => [
            'abstract', 'as', 'attribute', 'break', 'case', 'catch', 'class', 'clone', 'comment', 'const', 'const_import',
            'continue', 'do', 'echo', 'else', 'elseif', 'enum', 'extends', 'final', 'finally', 'for', 'foreach',
            'function', 'function_import', 'global', 'goto', 'implements', 'include', 'include_once', 'instanceof',
            'insteadof', 'interface', 'match', 'named_argument', 'namespace', 'new', 'open_tag_with_echo', 'php_doc',
            'php_open', 'print', 'private', 'protected', 'public', 'readonly', 'require', 'require_once', 'return',
            'static', 'switch', 'throw', 'trait', 'try', 'type_colon', 'use', 'use_lambda', 'use_trait', 'var', 'while',
            'yield', 'yield_from'
        ],
    ]);  // Ensures a single space after language constructs.


    //https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/blob/master/doc/rules/index.rst skończyłem na indentation_type
};
