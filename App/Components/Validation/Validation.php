<?php
namespace Backend\Components\Validation;

/**
 * Class Validation
 * @package Backend\Components\Validation
 */
class Validation extends \Phalcon\Validation
{
    /**
     * 验证类
     *
     * @var array $validation_types
     */
    protected static $validation_types = [
        // Check for alphanumeric character(s)
        'alnum'        => [
            'validator' => 'Phalcon\\Validation\\Validator\\Alnum'
        ],
        // Check for alphabetic character(s)
        'alpha'        => [
            'validator' => 'Phalcon\\Validation\\Validator\\Alpha'
        ],
        // Validates that a value is between an inclusive range of two values.
        'between'      => [
            'validator' => 'Phalcon\\Validation\\Validator\\Between'
        ],
        // Calls user function for validation
        'callback'     => [
            'validator' => 'Phalcon\\Validation\\Validator\\Callback'
        ],
        // Checks that two values have the same value
        'confirmation' => [
            'validator' => 'Phalcon\\Validation\\Validator\\Confirmation'
        ],
        // Checks if a value has a valid credit card number
        'creditcard'   => [
            'validator' => 'Phalcon\\Validation\\Validator\\CreditCard'
        ],
        // Checks if a value is a valid date
        'date'         => [
            'validator' => 'Phalcon\\Validation\\Validator\\Date'
        ],
        // Check for numeric character(s)
        'digit'        => [
            'validator' => 'Phalcon\\Validation\\Validator\\Digit'
        ],
        // Checks if a value has a correct e-mail format
        'email'        => [
            'validator' => 'Phalcon\\Validation\\Validator\\Email'
        ],
        // Check if a value is not included into a list of values
        'exclusionin'  => [
            'validator' => 'Phalcon\\Validation\\Validator\\ExclusionIn'
        ],
        // Checks if a value has a correct file
        'file'         => [
            'validator' => 'Phalcon\\Validation\\Validator\\File'
        ],
        // Checks if a value is identical to other
        'identical'    => [
            'validator' => 'Phalcon\\Validation\\Validator\\Identical'
        ],
        // Check if a value is included into a list of values
        'inclusionin'  => [
            'validator' => 'Phalcon\\Validation\\Validator\\InclusionIn'
        ],
        // Check for a valid numeric value
        'numericality' => [
            'validator' => 'Phalcon\\Validation\\Validator\\Numericality'
        ],
        // Validates that a value is not null or empty string
        'presenceof'   => [
            'validator' => 'Phalcon\\Validation\\Validator\\PresenceOf'
        ],
        // Allows validate if the value of a field matches a regular expression
        'regex'        => [
            'validator' => 'Phalcon\\Validation\\Validator\\Regex'
        ],
        // Validates that a string has the specified maximum and minimum constraints
        'stringlength' => [
            'validator' => 'Phalcon\\Validation\\Validator\\StringLength'
        ],
        // Check that a field is unique in the related table
        'uniqueness'   => [
            'validator' => 'Phalcon\\Validation\\Validator\\Uniqueness'
        ],
        // Checks if a value has a url format
        'url'          => [
            'validator' => 'Phalcon\\Validation\\Validator\\Url'
        ]
    ];

    /**
     *
     * 基本格式
     * [
     *  [验证字段1,验证类型[,错误提示,验证规则,验证条件,其他参数],
     *  [验证字段2,验证类型[,错误提示,验证规则,验证条件,其他参数],
     * ]
     *
     **************************************************************************************************
     *
     * 验证字段：
     * 通常为字段名，如果使用了映射，须与映射相对应，验证字段可以为数组（多个字段）
     * 例：[['username', 'age'], 'presenceof', ['username'=>'用户名不能为空','age'=>'年龄不能为空']]
     *
     * 支持的验证类型有(默认为regex):
     * 常用：regex（正则）、presenceof（不能为null or ''）、uniqueness（唯一）、inclusionin（在...之中）、uniqueness（检查一个字段在相关表中是惟一的【模型中使用】）
     * 全部：alnum（字母数字）、alpha（字母）、between、callback、confirmation、creditcard、date、digit（数字）、email、exclusionin、file、identical（检查一个值与指定固定值是否相同）、inclusionin、numericality、presenceof、regex、stringlength、uniqueness、url
     *
     * 错误提示：
     * 为字符串，多条错误提示信息用"|"分割，例如stringlength类型的验证，允许为空，为空启用默认提示
     *
     * 验证规则：
     * 额外的验证规则（为验证类型提供必要的参数）
     *
     * 验证条件 （默认为0）：
     * 1表示必须验证，0表示存在即验证
     *
     * 其他参数：
     * 验证类型需要的其他参数（详见下面详细说明）
     * 默认所有的验证器都会被执行，不管验证成功与否。 我们可以通过设置 cancelOnFail 参数为 true 来指定某个验证器验证失败时中止以后的所有验证
     * 例如把其他参数设为['cancelOnFail' => true]，表示失败后停止验证后面条件
     *
     **************************************************************************************************
     *
     * 详细说明：
     * 当验证类型为：alnum、alpha、creditcard、digit、email、numericality、url
     * 必须验证：[验证字段,验证类型[,错误提示,'',1]] or 存在则验证：[验证字段,验证类型[,错误提示]]
     * 例：['username', 'alnum', '用户名必须为数字字母', '', 1] 表示必须验证用户名且必须为数字字母
     *
     * 当验证类型为：between
     * [验证字段,验证类型,错误提示,验证规则[,验证条件]] 注：验证规则为数组[最小值,最大值],例：[5,12]
     * 例：['age', 'between', '年龄必须在0-150岁之间', [0,150]]
     * 注:请特别注意null、''等值，因为是直接用>和<进行比较,如果区间有0存在，建议增加一个presenceof验证
     *
     * 当验证类型为：callback
     * [验证字段,验证类型,错误提示,验证规则[,验证条件]] 注：验证规则为匿名函数
     * 例：
     * 在控制器中调用验证器的validate(var data = null, var entity = null)方法时，传递的是第一个参数，
     * 可以简单理解为：数据源如果是数组，则匿名函数的形参$data也是数组
     * ['age', 'callback', '年龄必须小于200', function($data){return isset($data['age']) && $data['age']<200 ? true:false; },1]
     *
     * 在模型中，调用的是模型中的validate(<ValidationInterface> validator)方法
     * 而该模型方法中调用验证器的validate(var data = null, var entity = null)方法时传递的是validate(null, $this),即传递的是当前模型对象
     * 所以可以简单理解为：模型中验证的数据源是模型对象，所以匿名函数的形参$data为对象
     * ['age', 'callback', '年龄必须小于210', function($data){$age = $data->age; return $age<210 ? true:false; //return $data->checkAge();}]
     *
     * 当验证类型为：confirmation
     * [验证字段,验证类型,错误提示,要与之比较的属性名[,验证条件]]
     * ['password', 'confirmation', '两次密码必须一致', 'confirmPassword'] 表示password存在时验证两次密码是否一致
     *
     * 当验证类型为：date
     * [验证字段,验证类型,错误提示,日期格式[,验证条件]]  注：  日期格式为date()函数的相关参数，例：'Y-m-d H:i:s'
     * 例：['start_time', 'date', '开始时间格式错误', 'Y-m-d H:i:s']
     *
     * 当验证类型为：exclusionin、inclusionin
     * [验证字段,验证类型,错误提示,验证规则[,验证条件]] 注：验证规则为数组,例：[1,2,'a']
     * 例：['status', 'inclusionin', '状态必须只能为-1或1或2', [-1,1,2], 1]
     * 注：由于使用的是in_array()判断，所以如果域（验证规则）中包含0,'',null,'0'等时建议加上第六个['strict' => true],用来检查搜索的数据与数组的值的类型是否相同
     * 即：['status', 'inclusionin', '状态必须只能为0或1或2', [0,1,2], 1, ['strict' => true]]
     *
     * 当验证类型为：identical
     * [验证字段,验证类型,错误提示,验证规则[,验证条件]] 注：验证规则为要比较的值
     * 例：['terms', 'identical', '必须接受条款和协议', 'yes', 1]  表示terms必须等于'yes'
     *
     * 当验证类型为：presenceof
     * 例：['username', 'presenceof', '用户名不能为空'] 表示用户名不能为空。 注：验证条件对该类型无效
     *
     * 当验证类型为：regex
     * [验证字段,验证类型,错误提示,验证规则[,验证条件]] 注：验证规则为正则表达式,例：'/^\d{1,9}$/'
     * 例：['tel', 'regex', '手机号格式错误', '/^1[34578]\d{9}$/', 1]
     *
     * 当验证类型为：file
     * [验证字段,验证类型,错误提示,验证规则,验证条件,其他参数]
     * 注：验证规则为数组形式：
     * [
     *      'maxSize'              => '2M',
     *      'messageSize'          => ':field exceeds the max filesize (:max)',
     *      'allowedTypes'         => [
     *          'image/jpeg',
     *          'image/png',
     *      ],
     *      'messageType'          => 'Allowed file types are :types',
     *      'maxResolution'        => '800x600',
     *      'messageMaxResolution' => 'Max resolution of :field is :max',
     * ]
     * 例：['filename', 'file', '文件大小必须小于2M', ['maxSize' => '2M'], 1]
     *
     * 当验证类型为：stringlength
     * [验证字段,验证类型,错误提示,验证规则[,验证条件]] 注：验证规则有三种，分别是[最小值，最大值]、<最大值、>最小值，当为数组形式时，错误信息要用“|”分割
     * 例：
     * ['username', 'stringlength', '用户名长度必须大于等于10|用户名长度必须小于等于12', [10,12], 1]
     * ['username', 'stringlength', '用户名长度必须大于等于10', '>=10', 1]
     * ['username', 'stringlength', '用户名长度必须小于等于12', '<=12', 1]
     *
     * 当验证类型为：uniqueness
     * [验证字段, 'uniqueness', 错误提示, 类名（加命名空间）, 验证条件[, 其他参数]] 注：类名允许为空，默认为当前调用模型，
     * 验证字段允许为数组（联合查询）
     * 其他参数允许为空，或为数组
     * [
     *      //匿名函数，验证前需要对数据进行处理的，非必须
     *      'convert' => function (array $values) {$values["username"] = md5($values["username"]);return $values;},
     *      //设置数据库真实字段,如果验证字段为数组，该值只会替换数组第一位，非必须
     *      'attribute' => 'user_name',
     * ]
     *</pre>
     * @desc 自动注册规则
     *
     * @param array $rules
     *
     * @return object
     * @throws \Exception
     */
    public function addRules(array $rules)
    {
        $types = self::$validation_types;
        foreach ($rules as $rule) {
            if (empty($rule[0])) {
                throw new \Exception('验证字段不能为空');
            }
            $field   = $rule[0];
            $type    = empty($rule[1]) ? 'regex' : $rule[1];
            $options = [];
            $rule[2] = $rule[2] ?? '';
            if (!empty($rule[2])) {
                $options['message'] = $rule[2];
            }
            $rule[3]               = $rule[3] ?? '';
            $rule[4]               = isset($rule[4]) ? $rule[4] : 0;
            $options['allowEmpty'] = $rule[4] == 0 ? true : false;
            switch ($type) {
                case 'alnum':
                case 'alpha':
                case 'creditcard':
                case 'digit':
                case 'email':
                case 'numericality':
                case 'url':
                    break;
                case 'confirmation':
                    $options['with'] = $rule[3];
                    break;
                case 'date':
                    $options['format'] = $rule[3];
                    break;
                case 'exclusionin':
                case 'inclusionin':
                    $options['domain'] = $rule[3];
                    break;
                case 'presenceof':
                    $options['allowEmpty'] = false;
                    break;
                case 'identical':
                    $options['accepted'] = $rule[3];
                    break;
                case 'regex':
                    $options['pattern'] = $rule[3];
                    break;
                case 'between':
                    $options['minimum'] = $rule[3][0] ?? null;
                    $options['maximum'] = $rule[3][1] ?? null;
                    break;
                case 'callback':
                    if (!is_object($rule[3])) {
                        throw new \Exception($rule[0] . ' 的验证规则必须是一个对象');
                    }
                    $options['callback'] = $rule[3];
                    break;
                case 'file':
                    if (!is_array($rule[3])) {
                        throw new \Exception($rule[0] . ' 的验证规则必须是一个数组');
                    }
                    $options = array_merge($options, $rule[3]);
                    break;
                case 'stringlength':
                    $options = $this->setStringLengthOpions($rule, $options);
                    break;
                case 'uniqueness':
                    $options = $this->setUniquenessOptions($rule, $options);
                    if (isset($rule[5])) {
                        unset($rule[5]);
                    }
                    break;
                default:
                    throw new \Exception('暂不支持' . $type . '验证类型');
            }
            if (!empty($rule[5]) && is_array($rule[5])) {
                $options = array_merge($options, $rule[5]);
            }
            $this->add($field, new $types[$type]['validator']($options));
        }
        return $this;
    }

    protected function setStringLengthOpions(array $rule, array $options = [])
    {
        if (is_array($rule[3])) {
            $options['min'] = $rule[3][0] ?? 0;
            $options['max'] = $rule[3][1] ?? 0;
            $message        = [];
            if (is_array($rule[2])) {
                $message = $rule[2];
            } else {
                if (is_string($rule[2])) {
                    $message = explode('|', $rule[2]);
                }
            }
            if (isset($message[0])) {
                $options['messageMinimum'] = $message[0];
            }
            if (isset($message[1])) {
                $options['messageMaximum'] = $message[1];
            }
        } else {
            if (is_string($rule[3])) {
                $symbol = substr($rule[3], 0, 2);
                $lenNum = substr($rule[3], 2);
                switch ($symbol) {
                    case '>=':
                        $lenNumKey  = 'min';
                        $messageKey = 'messageMinimum';
                        break;
                    case '<=':
                        $lenNumKey  = 'max';
                        $messageKey = 'messageMaximum';
                        break;
                    default:
                        throw new Exception('未检测到“>=”或“<=”');
                }
                $options[$lenNumKey]  = $lenNum;
                $options[$messageKey] = $rule[2];
            } else {
                throw new Exception('验证 “' . $rule[0] . '” stringlength参数类型错误');
            }
        }
        return $options;
    }

    /**
     * @desc   设置Uniqueness的option
     *
     * @param array $rule    单条规则
     * @param array $options 选项参数
     *
     * @return array
     */
    protected function setUniquenessOptions(array $rule, array $options = [])
    {
        if (!empty($rule[3])) {
            $options['model'] = new $rule[3]();
        }
        if (!empty($rule[5]) && is_array($rule[5])) {
            $options = array_merge($options, $rule[5]);
        }
        return $options;
    }
}