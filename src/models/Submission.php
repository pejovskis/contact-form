<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license MIT
 */

namespace craft\contactform\models;

use craft\base\Model;
use craft\web\UploadedFile;

/**
 * Class Submission
 *
 * @package craft\contactform
 */
class Submission extends Model
{
    /**
     * @var string|null
     */
    public $fromFName;

    /**
     * @var string|null
     */
    public $fromLName;

    /**
     * @var string|null
     */
    public $fromSubject;

    /**
     * @var string|null
     */
    public $fromEmail;

    /**
     * @var string|null
     */
    public $fromTelephone;

    /**
     * @var string|string[]|string[][]|null
     * @phpstan-var string|array<string|string[]>|null
     */
    public $message;

    /**
     * @var UploadedFile|UploadedFile[]|null[]|null
     * @phpstan-var UploadedFile|array<UploadedFile|null>|null
     */
    public $attachment;

    public $cbDataProtection;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fromFName' => \Craft::t('contact-form', 'Your Name'),
            'fromLName' => \Craft::t('contact-form', 'Your Last Name'),
            'fromEmail' => \Craft::t('contact-form', 'Your Email'),
            'fromSubject' => \Craft::t('contact-form', 'Subject'),
            'fromTelephone' => \Craft::t('contact-form', 'Telephone'),
            'message' => \Craft::t('contact-form', 'Message'),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        $rules = [];
        $fields = ['fromFName', 'fromLName', 'fromEmail', 'message', 'fromSubject', 'fromTelephone', 'cbDataProtection'];

        // Loop through each field and add a 'required' rule only if it exists in the request
        $request = \Craft::$app->getRequest();
        foreach ($fields as $field) {
            if ($request->getBodyParam($field) !== null) {
                $rules[] = [[$field], 'required'];
            }
        }

        if ($request->getBodyParam('fromEmail') !== null) {
            $rules[] = [['fromEmail'], 'email'];
        }

        return $rules;
    }
}
