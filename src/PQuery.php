<?php
namespace Pejman\DomParser;

class PQuery {
    public static $document;

    function __construct( $selector ) {
        if( is_string( $selector ) )
            $this->elements = self::$document->find( $selector );
        else if( is_array( $selector ) )
            $this->elements = $selector;
        else
            $this->elements = [ $selector ];
    }

    function each( $callback ) {
        foreach( $this->elements as $element ) {
            $callback( $element );
        }
        return $this;
    }

    function html( $html = '' ) {
        if( ! $html )
            return $this->elements[0]->html;

        return $this->each(function( $element ) {
            $element->html( $html );
        });
    }

    function attr( $key, $value = '' ) {
        if( ! $value ) return $this->elements[0]?$this->elements[0]->attr( $key ):'';
        return $this->each(function() {
            $element->attr( $key, $value );
        });
    }

    function next() {
        return pq( $this->elements[0]->next );
    }

    function prev() {
        return pq( $this->elements[0]->prev );
    }
}

?>