<?php

namespace App\Src;
include('./src/Context/ApplicationContext.php');
include('./src/Entity/Text.php');

use App\Src\Context\ApplicationContext;
use App\Src\Entity\Quote;
use App\Src\Entity\Text;
use App\Src\Entity\Template;
use App\Src\Entity\User;

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        if (!$data['quote']) {
            throw new \RuntimeException(('no quote given'));
        }

        $APPLICATION_CONTEXT = ApplicationContext::getInstance();
        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;
        $quoteAttributes = Quote::getMixedAttributes();
        $userAttributes = User::getMixedAttributes();
        $quoteDependencies = Quote::setQuoteDependencies($quote, $text, $quoteAttributes);
        $text = new Text($quoteDependencies, $quoteAttributes);
        $_user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();

        return $text->setUserAttributes($text->text, $userAttributes, $_user);
    }
}
