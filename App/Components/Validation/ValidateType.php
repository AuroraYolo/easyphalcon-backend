<?php
namespace Backend\Components\Validation;

/**
 * Class ValidateType
 * @package Backend\Components\Validation
 */
class ValidateType
{
    const ALNUM = 'alnum';
    const ALPHA = 'alpha';
    const DATE = 'date';
    const DIGIT = 'digit';
    const FILE = 'file';
    const UNIQUENESS = 'uniqueness';
    const NUMERICALITY = 'numericality';
    const PRESENCE_OF = 'presenceof';
    const IDENTICAL = 'identical';
    const EMAIL = 'email';
    const EXCLUSION_IN = 'exclusionIn';
    const REGEX = 'regex';
    const STRING_LENGTH = 'stringLength';
    const BETWEEN = 'between';
    const CONFIRMATION = 'confirmation';
    const URL = 'url';
    const CREDIT_CARD = 'creditCard';
    const CALLBACK = 'callback';
    const INCLUSION_IN = 'inclusionIn';
}
