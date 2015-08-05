<?php

namespace PhpAbac\Manager;

use PhpAbac\Abac;

use PhpAbac\Model\PolicyRule;

use PhpAbac\Repository\PolicyRuleRepository;

class PolicyRuleManager {
    /** @var PolicyRuleRepository **/
    protected $repository;
    
    public function __construct() {
        $this->repository = new PolicyRuleRepository();
    }
    
    /**
     * @param string $name
     * @param array $attributes
     * @return PolicyRule
     */
    public function create($name, $attributes) {
        $policyRule = $this->repository->createPolicyRule($name);
        
        $nbAttributes = count($attributes);
        
        for($i = 0; $i < $nbAttributes; ++$i) {
            $this->createPolicyRuleAttribute($policyRule, $attributes[$i]);
        }
        return $policyRule;
    }
    
    /**
     * @param PolicyRule $policyRule
     * @param array $data
     * @throws \InvalidArgumentException
     */
    public function createPolicyRuleAttribute($policyRule, $data) {
        if(!isset($data['comparison'])) {
            throw new \InvalidArgumentException('The attribute must have a comparison');
        }
        if(!isset($data['value'])) {
            throw new \InvalidArgumentException('The attribute must have a value');
        }
        if(!isset($data['attribute'])) {
            throw new \InvalidArgumentException('The attribute must have a key "attribute"');
        }
        $policyRule->addPolicyRuleAttribute($this
            ->repository
            ->createPolicyRuleAttribute(
                $policyRule->getId(),
                Abac::get('attribute-manager')->create(
                    $data['attribute']['name'],
                    $data['attribute']['table'],
                    $data['attribute']['column'],
                    $data['attribute']['criteria_column']
                ),
                $data['comparison'],
                $data['value']
            )
        );
    }
}