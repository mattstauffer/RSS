<?php

namespace Vinelab\Rss\Tests\Parsers;

use SimpleXMLElement;
use Vinelab\Rss\Feed;
use Vinelab\Rss\Parsers\XML;
use Vinelab\Rss\Feeds\RSSFeed;
use PHPUnit\Framework\TestCase;
use Vinelab\Rss\Feeds\AtomFeed;
use Vinelab\Rss\Contracts\FeedInterface;
use Vinelab\Rss\Exceptions\InvalidXMLException;
use Vinelab\Rss\Exceptions\InvalidFeedContentException;

class XMLTest extends TestCase
{
    public static $feed;

    public static $hunger;

    public static $invalid;

    public static $rss;

    public static $atom;

    private $xml;

    public static function setUpBeforeClass() : void
    {
        self::$hunger = 'something that is not XML';
        self::$feed = new SimpleXMLElement(file_get_contents(__DIR__ . '/samples/valid.xml'));
        self::$invalid = new SimpleXMLElement(file_get_contents(__DIR__ . '/samples/invalid.xml'));
        self::$rss = new SimpleXMLElement(file_get_contents(__DIR__.'/samples/0.92.rss.xml'));
        self::$atom = new SimpleXMLElement(file_get_contents(__DIR__.'/samples/atom.xml'));
    }

    public function setUp() : void
    {
        $this->xml = new XML();
    }

    public function test_parsing_valid_feed()
    {
        $feed = self::$feed;
        $feed = $this->xml->parse($feed);

        $this->assertInstanceOf(FeedInterface::class, $feed);
        $this->assertInstanceOf(Feed::class, $feed);

        $rss = $this->xml->parse(self::$rss);
        $this->assertInstanceOf(RSSFeed::class, $rss);

        $atom = $this->xml->parse(self::$atom);
        $this->assertInstanceOf(AtomFeed::class, $atom);
    }

    public function test_parsing_invalid_xml()
    {
        $this->expectException(InvalidXMLException::class);
        $this->xml->parse(self::$hunger);
    }

    public function test_parsing_invalid_feed()
    {
        $this->expectException(InvalidFeedContentException::class);
        $this->xml->parse(self::$invalid);
    }
}
