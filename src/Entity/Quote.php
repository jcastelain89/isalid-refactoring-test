<?php

namespace App\Src\Entity;

include('./src/Repository/QuoteRepository.php');
include('./src/Repository/DestinationRepository.php');
include('./src/Repository/SiteRepository.php');
use App\Src\Repository\DestinationRepository;
use App\Src\Repository\QuoteRepository;
use App\Src\Repository\SiteRepository;

class Quote
{
    public $id;
    public $siteId;
    public $destinationId;
    public $dateQuoted;

    public function __construct($id, $siteId, $destinationId, $dateQuoted)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = $dateQuoted;
    }

    public static function renderHtml(Quote $quote)
    {
        return '<p>' . $quote->id . '</p>';
    }

    public static function renderText(Quote $quote)
    {
        return (string) $quote->id;
    }

    public static function getMixedAttributes() {
        return array(
            'destination_link' => '[quote:destination_link]',
            'destination_name' => '[quote:destination_name]',
            'summary' => '[quote:summary]',
            'summary_html' => '[quote:summary_html]',
        );
    }

    public static function setQuoteDependencies($quote, $text, $quoteAttributes) {
        if ($quote)
        {
            $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->id);
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);

            return array(
                '_quoteFromRepository' => $_quoteFromRepository,
                'usefulObject' => SiteRepository::getInstance()->getById($quote->siteId),
                'destinationOfQuote' => $destinationOfQuote,
                'destination' => Destination::setDestination($text, $quoteAttributes, $quote->destinationId),
                'text' => self::setQuoteAttributes($text, $quoteAttributes, $_quoteFromRepository, $destinationOfQuote),
            );
       }
    }

    public static function setQuoteAttributes($text, $quoteAttributes, $_quoteFromRepository, $destinationOfQuote) {
        if (strpos($text, $quoteAttributes['summary_html'])) {
            $text = str_replace(
                $quoteAttributes['summary_html'],
                Quote::renderHtml($_quoteFromRepository),
                $text
            );
        }

        if (strpos($text, $quoteAttributes['summary'])) {
            $text = str_replace(
                $quoteAttributes['summary'],
                Quote::renderText($_quoteFromRepository),
                $text
            );
        }

        (strpos($text, $quoteAttributes['destination_name'])) and $text = str_replace($quoteAttributes['destination_name'],$destinationOfQuote->countryName,$text);

        return $text;
    }
}
