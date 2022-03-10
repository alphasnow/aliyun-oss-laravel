<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use Illuminate\Support\Facades\Storage;

class FeatureTest extends TestCase
{
    public function testFeature()
    {
        $file = __DIR__.'/stubs/file.txt';

        $res1 = Storage::disk('oss')->putFile('files', $file);
        $res2 = Storage::disk('oss')->putFileAs('files', $file, 'file.txt');

        $res3 = Storage::disk('oss')->put('texts/file3.txt', file_get_contents($file));
        $fp = fopen($file, 'r');
        $res4 = Storage::disk('oss')->put('texts/file4.txt', $fp);
        fclose($fp);

        $res5 = Storage::disk('oss')->prepend('file5.txt', 'Prepend Text');
        $res6 = Storage::disk('oss')->append('file5.txt', 'Append Text');

        $res7 = Storage::disk('oss')->put('file7.txt', 'My secret', 'private');
        $res8 = Storage::disk('oss')->put('file8.txt', 'Download content', ["headers" => ["Content-Disposition" => "attachment; filename=file.txt"]]);

        $res9 = Storage::disk('oss')->url('files/file.txt');
        $res10 = Storage::disk('oss')->temporaryUrl('files/file.txt', \Carbon\Carbon::now()->addMinutes(30));

        $res11 = Storage::disk('oss')->get('files/file.txt');

        $res12 = Storage::disk('oss')->exists('files/file.txt');
        $res13 = Storage::disk('oss')->size('files/file.txt');
        $res14 = Storage::disk('oss')->lastModified('files/file.txt');

        $res15 = Storage::disk('oss')->copy('files/file.txt', 'files/file_new.txt');
        $res16 = Storage::disk('oss')->move('files/file_new.txt', 'files/file_move.txt');

        $res17 = Storage::disk('oss')->delete('files/file_move.txt');

        $res18 = Storage::disk('oss')->makeDirectory('others/first');
        $res19 = Storage::disk('oss')->deleteDirectory('others/first');

        $res20 = Storage::disk('oss')->files('files');
        $res21 = Storage::disk('oss')->allFiles('texts');

        $res22 = Storage::disk('oss')->directories('/');
        $res23 = Storage::disk('oss')->allDirectories('/');
    }

    public function testMacro()
    {
        Storage::disk('oss')->appendObject('objects/news.txt', 'The first line paragraph.', 0);
        Storage::disk('oss')->appendObject('objects/news.txt', 'The second line paragraph.', 25);
        Storage::disk('oss')->appendObject('objects/news.txt', 'The last line paragraph.', 51);

        $file = __DIR__.'/stubs/file.txt';
        $position001 = Storage::disk('oss')->appendFile('objects/file.txt', $file, 0);
        $position002 = Storage::disk('oss')->appendFile('objects/file.txt', $file, $position001);
        $position003 = Storage::disk('oss')->appendFile('objects/file.txt', $file, $position002);
    }
}
