<?php

namespace com\getinstance\util {

    class Debug
    {
        public static function helloWorld(): void
        {
            print "hello from Debug\n";
        }
    }
}

namespace other {

    \com\getinstance\util\Debug::helloWorld();
}
