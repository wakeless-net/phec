<?php

namespace Phec;

class Wrapper {
  function __construct($file) {
    include $file;
  }
}

