<?php

App::uses('Component', 'Controller');
class PlaylistComponent extends Component {
    public function doComplexOperation($amount1, $amount2) {
        return $amount1 + $amount2;
    }
}
?>