<?php

/**
 * WIKI:
 * https://ru.wikipedia.org/wiki/%D0%9F%D0%BE%D1%81%D0%B5%D1%82%D0%B8%D1%82%D0%B5%D0%BB%D1%8C_(%D1%88%D0%B0%D0%B1%D0%BB%D0%BE%D0%BD_%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F)
 */

	interface Visitor {
		public function visit ( Point $point );
	}

	abstract class Point {
		public abstract function accept ( Visitor $visitor );
		private $_metric = -1;
		public function getMetric () {
			return $this->_metric;
		}
		public function setMetric ( $metric ) {
			$this->_metric = $metric;
		}
	}

	class Point2d extends Point {

		public function __construct ( $x, $y ) {
			$this->_x = $x;
			$this->_y = $y;
		}

		public function accept ( Visitor $visitor ) {
			$visitor->visit( $this );
		}

		private $_x;
		public function getX () { return $this->_x; }

		private $_y;
		public function getY () { return $this->_y; }
	}

	class Point3d extends Point {
		public function __construct ( $x, $y, $z ) {
			$this->_x = $x;
			$this->_y = $y;
			$this->_z = $z;
		}

		public function accept ( Visitor $visitor ) {
			$visitor->visit( $this );
		}

		private $_x;
		public function getX () { return $this->_x; }

		private $_y;
		public function getY () { return $this->_y; }

		private $_z;
		public function getZ () { return $this->_z; }
	}

	class Euclid implements Visitor {
		public function visit ( Point $p ) {
			if($p instanceof Point2d)
				$p->setMetric( sqrt( $p->getX()*$p->getX() + $p->getY()*$p->getY() ) );
			elseif( $p instanceof Point3d)
				$p->setMetric( sqrt( $p->getX()*$p->getX() + $p->getY()*$p->getY() + $p->getZ()*$p->getZ() ) );
		}
	}

	class Chebyshev implements Visitor {
		public function visit ( Point $p ) {
			if($p instanceof Point2d){
				$ax = abs( $p->getX() );
				$ay = abs( $p->getY() );
				$p->setMetric( $ax>$ay ? $ax : $ay );
			}
			elseif( $p instanceof Point3d){
				$ax = abs( $p->getX() );
				$ay = abs( $p->getY() );
				$az = abs( $p->getZ() );
				$max = $ax>$ay ? $ax : $ay;
				if ( $max<$az ) $max = $az;
				$p->setMetric( $max );
			}
		}
	}


	function start(){
		$p = new Point2d( 1, 2 );
		$v = new Chebyshev();
		$p->accept( $v );
		echo ( $p->getMetric() );
	};
	start();