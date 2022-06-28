<?php

namespace AlphaSnow\LaravelFilesystem\Aliyun\Tests;

use Illuminate\Support\Facades\Storage;

class ActualUseTest extends TestCase
{
    /**
     * @test
     */
    public function storage_method()
    {
        $file = __DIR__."/stubs/file.txt";

        $result = [];
        $result[] = Storage::disk("oss")->putFile("files", $file);
        $result[] = Storage::disk("oss")->putFileAs("files", $file, "file.txt");

        $result[] = Storage::disk("oss")->put("texts/file3.txt", file_get_contents($file));
        $fp = fopen($file, "r");
        $result[] = Storage::disk("oss")->put("texts/file4.txt", $fp);
        fclose($fp);

        $result[] = Storage::disk("oss")->prepend("file5.txt", "Prepend Text");
        $result[] = Storage::disk("oss")->append("file5.txt", "Append Text");

        $result[] = Storage::disk("oss")->put("file7.txt", "My secret", "private");
        $result[] = Storage::disk("oss")->put("file8.txt", "Download content", ["headers" => ["Content-Disposition" => "attachment; filename=file.txt"]]);

        $result[] = Storage::disk("oss")->url("files/file.txt");
        $result[] = Storage::disk("oss")->temporaryUrl("files/file.txt", \Carbon\Carbon::now()->addMinutes(30));

        $result[] = Storage::disk("oss")->get("files/file.txt");

        $result[] = Storage::disk("oss")->exists("files/file.txt");
        $result[] = Storage::disk("oss")->size("files/file.txt");
        $result[] = Storage::disk("oss")->lastModified("files/file.txt");

        $result[] = Storage::disk("oss")->copy("files/file.txt", "files/file_new.txt");
        $result[] = Storage::disk("oss")->move("files/file_new.txt", "files/file_move.txt");

        $result[] = Storage::disk("oss")->delete("files/file_move.txt");

        $result[] = Storage::disk("oss")->makeDirectory("others/first");
        $result[] = Storage::disk("oss")->deleteDirectory("others/first");

        $result[] = Storage::disk("oss")->files("files");
        $result[] = Storage::disk("oss")->allFiles("texts");

        $result[] = Storage::disk("oss")->directories("/");
        $result[] = Storage::disk("oss")->allDirectories("/");

        $this->assertFalse(in_array(false, $result));
    }

    /**
     * @test
     */
    public function macro_append_object()
    {
        $result = [];
        $result[] = Storage::disk("oss")->appendObject("objects/text.txt", "The first line paragraph.", 0);
        $result[] = Storage::disk("oss")->appendObject("objects/text.txt", "The second line paragraph.", 25);
        $result[] = Storage::disk("oss")->appendObject("objects/text.txt", "The last line paragraph.", 51);
        Storage::disk("oss")->delete("objects/text.txt");

        $this->assertSame([25,51,75], $result);
    }

    /**
     * @test
     */
    public function macro_append_file()
    {
        $file = __DIR__."/stubs/file.txt";
        $result = [];
        $result[] = Storage::disk("oss")->appendFile("objects/file.txt", $file, 0);
        $result[] = Storage::disk("oss")->appendFile("objects/file.txt", $file, $result[0]);
        $result[] = Storage::disk("oss")->appendFile("objects/file.txt", $file, $result[1]);
        Storage::disk("oss")->delete("objects/file.txt");

        $this->assertSame([7,14,21], $result);
    }
}
