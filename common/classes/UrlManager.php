<?php

namespace common\classes;

use Yii;
use yii\web\UrlRule;
use yii\web\UrlRuleInterface;
use yii\base\InvalidConfigException;

/**
 * Description of UrlManager
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class UrlManager extends \yii\web\UrlManager
{
    /**
     * @inheritdoc
     */
    protected function buildRules($rules)
    {
        $compiledRules = [];
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
        foreach ($rules as $key => $rule) {
            if (is_string($rule) || (is_array($rule) && isset($rule[0]))) {
                if (is_string($rule)) {
                    $rule = ['route' => $rule];
                } else {
                    $rule = [
                        'route' => $rule[0],
                        'defaults' => $rule,
                    ];
                    unset($rule['defaults'][0]);
                }
                
                if (preg_match("/^((?:($verbs),)*($verbs))\\s+(.*)$/", $key, $matches)) {
                    $rule['verb'] = explode(',', $matches[1]);
                    // rules that do not apply for GET requests should not be use to create urls
                    if (!in_array('GET', $rule['verb'])) {
                        $rule['mode'] = UrlRule::PARSING_ONLY;
                    }
                    $key = $matches[4];
                }
                $rule['pattern'] = $key;
            }
            if (is_array($rule)) {
                $rule = Yii::createObject(array_merge($this->ruleConfig, $rule));
            }
            if (!$rule instanceof UrlRuleInterface) {
                throw new InvalidConfigException('URL rule class must implement UrlRuleInterface.');
            }
            $compiledRules[] = $rule;
        }
        return $compiledRules;
    }
}
