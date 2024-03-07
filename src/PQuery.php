<?php
namespace Pejman\DomParser;

class PQuery {
    public static $document;
    private $elements;

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

    function outerHtml() {
        return $this->elements[0]->outerHtml();
    }

    function addClass( $cls = '' ) {
        return $this->each(function( $element ) use( $cls ) {
            if( $element->attrs )
                $element->attrs->addClass( $cls );
        });
    }

    function removeClass( $cls ) {
        return $this->each(function( $element ) use( $cls ) {
            if( $element->attrs )
                $element->attrs->removeClass( $cls );
        });
    }

    function html( $html = '' ) {
        if( ! $html )
            return $this->elements[0]->html;

        return $this->each(function( $element ) use( $html ) {
            $element->html( $html );
        });
    }

    function attr( $key, $value = '' ) {
        if( ! $value ) return $this->elements[0]?$this->elements[0]->attr( $key ):'';
        return $this->each(function( $element ) use( $key, $value ) {
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
