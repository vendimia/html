<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Vendimia\Html\OptionTags;

require __DIR__ . '/../vendor/autoload.php';

final class OptionTagsTest extends TestCase
{
    public function testSimpleOptionList()
    {
        $options = new OptionTags(["Blue", "Green"]);

        $this->assertEquals(
            '<option>Blue</option><option>Green</option>',
            (string)$options
        );
    }

    public function testOptionListWithValues()
    {
        $options = new OptionTags([
            'p' => 'Person',
            'c' => 'Company',
        ]);

        $this->assertEquals(
            '<option value="p">Person</option><option value="c">Company</option>',
            (string)$options
        );
    }

    public function testOptionListWithSelected()
    {
        $options = new OptionTags([
            'p' => 'Person',
            'c' => 'Company',
        ], selected_list:['p']);

        $this->assertEquals(
            '<option value="p" selected="true">Person</option><option value="c">Company</option>',
            (string)$options
        );
    }

    public function testOptionListWithOptGroup()
    {
        $options = new OptionTags([
            'humans' => [
                'p' => 'Person',
                'c' => 'Company',
            ],
            'animals' => [
                'd' => 'Dogs',
                'b' => 'Birds',
            ]
        ], selected_list:['p']);

        $this->assertEquals(
            '<optgroup label="humans"><option value="p" selected="true">Person</option><option value="c">Company</option></optgroup><optgroup label="animals"><option value="d">Dogs</option><option value="b">Birds</option></optgroup>',
            (string)$options
        );
    }

}