<?php

$this->dispatcher->connect('op_action.pre_execute', array('opHostingBetaEvent', 'checkUserLimit'));