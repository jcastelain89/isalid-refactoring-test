<?php

class TemplateManager
{
    private function getQuoteAttributes() {
        return array(
            'destination_link' => '[quote:destination_link]',
            'destination_name' => '[quote:destination_name]',
            'summary' => '[quote:summary]',
            'summary_html' => '[quote:summary_html]',
        );
    }

    private function getUserAttributes() {
        return array(
            'first_name' => '[user:first_name]'
        );
    }

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

        $quoteAttributes = $this->getQuoteAttributes();

        $userAttributes = $this->getUserAttributes();

        if ($quote)
        {
            $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->id);
            $usefulObject = SiteRepository::getInstance()->getById($quote->siteId);
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);

            if(strpos($text, $quoteAttributes['destination_link'])){
                $destination = DestinationRepository::getInstance()->getById($quote->destinationId);
            }

            $containsSummaryHtml = strpos($text, $quoteAttributes['summary_html']);
            $containsSummary     = strpos($text, $quoteAttributes['summary']);

            if ($containsSummaryHtml) {
                $text = str_replace(
                    $quoteAttributes['summary_html'],
                    Quote::renderHtml($_quoteFromRepository),
                    $text
                );
            }
            if ($containsSummary) {
                $text = str_replace(
                    $quoteAttributes['summary'],
                    Quote::renderText($_quoteFromRepository),
                    $text
                );
            }

            (strpos($text, $quoteAttributes['destination_name'])) and $text = str_replace($quoteAttributes['destination_name'],$destinationOfQuote->countryName,$text);
        }

        if (isset($destination))
            $text = str_replace($quoteAttributes['destination_link'], $usefulObject->url . '/' . $destination->countryName . '/quote/' . $_quoteFromRepository->id, $text);
        else
            $text = str_replace($quoteAttributes['destination_link'], '', $text);

        /*
         * USER
         * [user:*]
         */
        $_user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();
        if($_user) {
            (strpos($text, $userAttributes['first_name'])) and $text = str_replace($userAttributes['first_name'], ucfirst(mb_strtolower($_user->firstname)), $text);
        }

        return $text;
    }
}
