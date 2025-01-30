<?php
try {
    eval("illegal code");
} catch (\Error $e) {
    print get_class($e) . "\n";
    print $e->getMessage();
} catch (\Exception $e) {
    // do something with an Exception
}
