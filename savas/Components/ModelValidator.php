<?php

namespace savas\Components;

use Favez\ORM\Entity\Entity;
use Validator\Validator;

class ModelValidator
{

    /**
     * @var Validator
     */
    protected $validator;

    public function validate(Entity $entity)
    {
        $this->validator = new Validator();

        if (method_exists($entity, 'validate'))
        {
            $fields = $entity->validate();

            foreach ($fields as $fieldName => $rules)
            {
                $rulesStr = implode('|', array_keys($rules));

                foreach ($rules as $rule => $message)
                {
                    if (($pos = strpos($rule, ':')) !== false)
                    {
                        $fixed = substr($rule, 0, $pos);

                        $rules[$fixed] = $message;
                        unset ($rules[$rule]);
                    }
                }

                $this->validator->add($fieldName, $entity->get($fieldName), $rulesStr, $rules);
            }

            $this->validator->validate();

            return $this->validator->passes();
        }

        return true;
    }

    public function getMessages()
    {
        return $this->validator->errors();
    }

}