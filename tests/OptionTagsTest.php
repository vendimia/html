<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Vendimia\Html\OptionTags;

require __DIR__ . '/../vendor/autoload.php';

final class OptionTagsTest extends TestCase
{
    public function testCreateSimpleOptionList()
    {
        $options = new OptionTags(["Blue", "Green"]);

        $this->assertEquals(
            '<option>Blue</option><option>Green</option>',
            (string)$options
        );
    }

    public function testCreateOptionListWithValues()
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

    public function testCreateOptionListWithSelected()
    {
        $options = new OptionTags([
            'p' => 'Person',
            'c' => 'Company',
        ], select_list:['p']);

        $this->assertEquals(
            '<option value="p" selected="true">Person</option><option value="c">Company</option>',
            (string)$options
        );
    }

}