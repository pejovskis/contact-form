<?php

namespace craft\contactform\controllers;

use Craft;
use craft\contactform\models\Submission;
use craft\contactform\Plugin;
use craft\web\Controller;
use craft\web\UploadedFile;
use yii\web\Response;
use GuzzleHttp\Client;

class SendController extends Controller
{
    /**
     * @inheritdoc
     */
    public array|bool|int $allowAnonymous = true;

    /**
     * Sends a contact form submission.
     *
     * @return Response|null
     */
    public function actionIndex()
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();
        $plugin = Plugin::getInstance();
        $settings = $plugin->getSettings();

        // set recipient
        $settings->toEmail = $request->getBodyParam('recipient');

        $submission = new Submission();
        $submission->fromFName = $request->getBodyParam('fromFName');
        $submission->fromLName = $request->getBodyParam('fromLName');
        $submission->fromCompany = $request->getBodyParam('fromCompany');
        $submission->fromSubject = $request->getBodyParam('fromSubject');
        $submission->fromEmail = $request->getBodyParam('fromEmail');
        $submission->fromTelephone = $request->getBodyParam('fromTelephone');
        $submission->cbDataProtection = $request->getBodyParam('cbDataProtection');
        $submission->fromCustomerLink = $request->getBodyParam('fromCustomerLink');
        $submission->fromCustomerWebsite = $request->getBodyParam('fromCustomerWebsite');

        $message = $request->getBodyParam('message');
        if (is_array($message)) {
            $submission->message = array_filter($message, function($value) {
                return $value !== '';
            });
        } else {
            $submission->message = $message;
        }

        if ($settings->allowAttachments && isset($_FILES['attachment']) && isset($_FILES['attachment']['name'])) {
            if (is_array($_FILES['attachment']['name'])) {
                $submission->attachment = UploadedFile::getInstancesByName('attachment');
            } else {
                $submission->attachment = UploadedFile::getInstanceByName('attachment');
            }
        }

        if (!$plugin->getMailer()->send($submission)) {
            return $this->asModelFailure(
                $submission,
                Craft::t('contact-form', 'There was a problem with your submission, please check the form and try again!'),
                'submission',
                [
                    'errors' => $submission->getErrors(),
                ],
            );
        }

        return $this->asModelSuccess(
            $submission,
            $settings->successFlashMessage,
            'submission',
        );
    }

}
