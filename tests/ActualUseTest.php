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
        $result[] = Storage::disk("oss")->putFileAs("files", $file, "file1.txt");

        $result[] = Storage::disk("oss")->put("contents/file2.txt", file_get_contents($file));
        $fp = fopen($file, "r");
        $result[] = Storage::disk("oss")->put("contents/file3.txt", $fp);
        fclose($fp);

        $result[] = Storage::disk("oss")->prepend("contents/file4.txt", "Prepend Text");
        $result[] = Storage::disk("oss")->append("contents/file5.txt", "Append Text");

        $result[] = Storage::disk("oss")->put("contents/file6.txt", "My secret", "private");
        $result[] = Storage::disk("oss")->put("contents/file7.txt", "Download content", ["headers" => ["Content-Disposition" => "attachment; filename=file.txt"]]);

        $result[] = Storage::disk("oss")->temporaryUrl("contents/file6.txt", \Carbon\Carbon::now()->addMinutes(30));
        $result[] = Storage::disk("oss")->url("contents/file7.txt");

        $result[] = Storage::disk("oss")->getVisibility("contents/file5.txt");
        $result[] = Storage::disk("oss")->setVisibility("contents/file5.txt", "private");

        $result[] = Storage::disk("oss")->get("files/file1.txt");
        $result[] = Storage::disk("oss")->readStream("files/file1.txt");

        $result[] = Storage::disk("oss")->exists("files/file1.txt");
        $result[] = Storage::disk("oss")->size("files/file1.txt");
        $result[] = Storage::disk("oss")->lastModified("files/file1.txt");

        $result[] = Storage::disk("oss")->copy("files/file1.txt", "files/file2.txt");
        $result[] = Storage::disk("oss")->move("files/file2.txt", "files/file3.txt");
        $result[] = Storage::disk("oss")->delete("files/file3.txt");

        $result[] = Storage::disk("oss")->files("files/");
        $result[] = Storage::disk("oss")->put("contents/inside/file.txt", "contents");
        $result[] = Storage::disk("oss")->allFiles("contents/");

        $result[] = Storage::disk("oss")->directories("/");
        $result[] = Storage::disk("oss")->makeDirectory("files/inside/backup");
        $result[] = Storage::disk("oss")->allDirectories("/");
        $result[] = Storage::disk("oss")->deleteDirectory("files/inside");

        $this->assertFalse(in_array(false, $result));
    }

    /**
     * @test
     */
    public function macro_append_object()
    {
        $result = [];
        $result[] = Storage::disk("oss")->appendObject("contents/file8.txt", "The first line paragraph.", 0);
        $result[] = Storage::disk("oss")->appendObject("contents/file8.txt", "The second line paragraph.", 25);
        $result[] = Storage::disk("oss")->appendObject("contents/file8.txt", "The last line paragraph.", 51);
        Storage::disk("oss")->delete("contents/file8.txt");

        $this->assertSame([25,51,75], $result);
    }

    /**
     * @test
     */
    public function macro_append_file()
    {
        $file = __DIR__."/stubs/file.txt";
        $result = [];
        $result[] = Storage::disk("oss")->appendFile("files/file9.txt", $file, 0);
        $result[] = Storage::disk("oss")->appendFile("files/file9.txt", $file, $result[0]);
        $result[] = Storage::disk("oss")->appendFile("files/file9.txt", $file, $result[1]);
        Storage::disk("oss")->delete("files/file9.txt");

        $this->assertSame([7,14,21], $result);
    }
}
