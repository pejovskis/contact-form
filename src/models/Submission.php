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
    public $fromCustomerLink;
    public $fromCustomerWebsite;
    public $fromCompany;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fromFName' => \Craft::t('contact-form', 'Your Name'),
            'fromLName' => \Craft::t('contact-form', 'Your Last Name'),
            'fromSubject' => \Craft::t('contact-form', 'Subject'),
            'fromEmail' => \Craft::t('contact-form', 'Your Email'),
            'fromCompany' => \Craft::t('contact-form', 'Company'),
            'fromTelephone' => \Craft::t('contact-form', 'Telephone'),
            'message' => \Craft::t('contact-form', 'Message'),
            'fromCustomerLink' => \Craft::t('contact-form', 'Customer Link'),
            'fromCustomerWebsite' => \Craft::t('contact-form', 'Customer Website'),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        return [
            [['fromEmail', 'cbDataProtection'], 'required'],
            [['fromEmail'], 'email'],
            [['fromFName', 'fromLName', 'fromSubject', 'fromTelephone', 'message', 'fromCustomerLink', 'fromCustomerWebsite', 'fromCompany'], 'safe'],
        ];
    }

}
