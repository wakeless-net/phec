<?php

/**
 * @whatever
 */
describe("allowing tags", function() {
   $this->it("should have tags", function() {
     $this->expects($this->tags())->equals("whatever");
   });
});
