<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Vendimia\Html\Tag;

require __DIR__ . '/../src/Tag.php';

final class TagTest extends TestCase
{
    public function testCreateSimpleTag()
    {
        $tag = new Tag('br');
        $this->assertEquals('<br />', (string)$tag);
    }

    public function testCreateSimpleTagWithStaticCall()
    {
        $tag = Tag::br();
        $this->assertEquals('<br />', (string)$tag);
    }

    public function testCreateTagWithAttributes()
    {
        $tag = new Tag('link', ['href' => 'css/style.css']);
        $this->assertEquals('<link href="css/style.css" />', (string)$tag);
    }

    public function testCreateTagWithAttributesAndStaticCall()
    {
        $tag = Tag::link(href: 'css/style.css');
        $this->assertEquals('<link href="css/style.css" />', (string)$tag);
    }

    public function testCreateTagWithContent()
    {
        $tag = new Tag('button', ["type" => "submit"], 'Save');
        $this->assertEquals('<button type="submit">Save</button>', (string)$tag);
    }

    public function testCreateTagWithContentAndStaticCall()
    {
        $tag = Tag::button(type: 'submit')('Save');
        $this->assertEquals('<button type="submit">Save</button>', (string)$tag);
    }

    public function testAddPropertiesUsingArrayAccess()
    {
        $tag = new Tag('textarea');
        $tag['name'] = 'comments';
        $tag->closeTag();

        $this->assertEquals('<textarea name="comments"></textarea>', (string)$tag);
    }

    public function testShouldEscapeHtmlContent()
    {
        $tag = new Tag('p', content: "This is a <p> tag");
        $this->assertEquals('<p>This is a &lt;p&gt; tag</p>', (string)$tag);
    }    

    public function testUnescapeHtmlContentAndStaticCall()
    {
        $tag = Tag::p("It's <strong>bold</strong>")->noEscapeContent();
        $this->assertEquals("<p>It's <strong>bold</strong></p>", (string)$tag);
    }    
}