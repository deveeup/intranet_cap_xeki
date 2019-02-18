<?php
namespace Agent;

class Ecommerce {
	protected $AG_USER;
	protected $_INFO_SECCION;
	protected $sql;
	public function __construct($AG_USER = null, $_INFO_SECCION = null, $sql = null) {
		$this->AG_USER = $AG_USER;
		$this->_INFO_SECCION = $_INFO_SECCION;
		$this->sql = $sql;

	}

	function validateCupon($cupon_code) {
		$cupon_code = $cupon_code;
		$AG_USER = $this->AG_USER;
		$_INFO_SECCION = $this->_INFO_SECCION;
		$sql = $this->sql;

		$query = "SELECT * FROM cupones WHERE code='" . $cupon_code . "' and (fecha_limite > now() or fecha_limite=0000-00-00)";
		$data = $sql->query($query);
		# valid cupon
		if (count($data) == 1) {
			$isOk = true;
			$errMessage = true;
			$message = '¡Cupon Valido!';
			$messageExtra = '';
			## check si el cliente ya hizo una compra
			$data = $data[0]['cupones'];
			if ($data['primeraCompra'] == 'Si') {
				$query = "SELECT * FROM client_pedidos WHERE client='" . $AG_USER['id'] . "'"; //TODO agregar compra valida
				if (count($sql->query($query)) > 0) {
					$isOk = false;
					$errMessage .= 'Este cupon solo es valido para la primera compra.';
				}
			}

			if ($data['unSoloUsoPerPerson'] == 'Si') {
				$query = "SELECT * FROM client_cuponesRedimidos WHERE client_Ref='" . $AG_USER['id'] . "' and codeCupon='" . $cupon_code . "'"; //TODO agregar compra valida
				if (count($sql->query($query)) > 0) {
					$isOk = false;
					$errMessage .= 'Este cupon ya lo has usado.';
				}
			}

			if ($data['unSoloUso'] == 'Si') {
				$query = "SELECT * FROM client_cuponesRedimidos WHERE codeCupon='" . $cupon_code . "'"; //TODO agregar compra valida
				if ($data['usado'] == 'Si') {
					$isOk = false;
					$errMessage .= 'Este cupon ya ha sido usado.';
				}
			}

			if ($data['precioMinino'] > 0) {
				$message = 'Este cupon se aplica apartir de $' . formatcurrency($data['precioMinino']) . ' en tu compra.';
				$messageExtra = 'El cupon activo se aplica apartir de $' . formatcurrency($data['precioMinino']) . ' en tu compra.';
			}

			# check si el cliente ya uso el cupon
			if ($isOk) {
				$_SESSION['cupon'] = $cupon_code;
				$_SESSION['cuponMessage'] = $messageExtra;
				ag_pushMsg($message);
			} else {
				ag_pushMsg($errMessage);
			}

		} else {
			ag_pushMsg('El cupon no es valido');
		}
	}
	function calculateCart() {
		$AG_USER = $this->AG_USER;
		$_INFO_SECCION = $this->_INFO_SECCION;
		$sql = $this->sql;

		$data = array();
		$userFf = getUser();

		## valid and load cupon
		$dataCupon = array();
		$excludeCategories = array();
		$includeCategories = array();
		$includeProducts = array();
		if (isset($_INFO_SECCION['cupon'])) {
			$query = "SELECT * FROM cupones WHERE code='" . $_INFO_SECCION['cupon'] . "'";
			$dataCupon = $sql->query($query);
			$dataCupon = $dataCupon[0]['cupones'];

			$query = 'SELECT * FROM cupon_exclude_categories,product_categorias where product_categorias.id=cupon_exclude_categories.product_categorias and cupon_exclude_categories.cupones=' . $dataCupon['id'];
			$excludeCategories = $sql->query($query);

			if ($dataCupon['someCategories'] == 'Si') {
				$query = 'SELECT * FROM cupon_valid_categories,product_categorias where product_categorias.id=cupon_valid_categories.product_categorias and cupon_valid_categories.cupones=' . $dataCupon['id'];
				$includeCategories = $sql->query($query);
			}

			if ($dataCupon['someProducts'] == 'Si') {
				$query = 'SELECT * FROM cupon_valid_products,product where cupon_valid_products.product=product.id and cupon_valid_products.cupones=' . $dataCupon['id'];
				$includeProducts = $sql->query($query);
			}

		}

		$descuentoPorcentaje = 0;
		$descuentValor = 0;
		$descuentoEnvio = 0;
		$precioMinino = 0;
		$soloUnaVez = false;
		$unSoloUsoPerPerson = false;
		if (isset($dataCupon['activo'])) {
			$descuentoPorcentaje = $dataCupon['descuentoPrecio'];
			$descuentValor = $dataCupon['descuentoValor'];
			$descuentoEnvio = $dataCupon['descuentoEnvio'];
			$precioMinino = $dataCupon['precioMinino'];
		}

		## sting html prints
		$carrito1 = '';
		$carrito2 = ''; # big cart
		$carrito3 = ''; # inner cart  /shopingCartFix
		$carrito4 = '';
		$carrito5 = ''; # just resume

		## data ini
		$carrito1 .= '<div class="orderSumary">
	    <div class="title">
	        Resumen Pedido
	    </div>';
		$carrito2 .= '
	        <div class="itemTitle">
	            <div class="producto">Producto</div>
	            <div class="productoPrecio">Precio</div>
	            <div class="productoUnidades">Unidades</div>
	            <div class="productoTotal">Subtotal</div>
	        </div> ';

		$listData = ''; #list data for email info

		## producots
		$productos = 0;
		$totalGE = 0;
		$peso = 0.0;
		$envio = 0;
		$impuestos = 0;

		# valid descuento
		$validDescuento = true;
		$descuentoTotalSumado = 0;
		$descuentoProductos = 0;
		$descuentoEnvioPesos = 0;

		$query = "SELECT * FROM client_carrito,product where product.id=client_carrito.producto and client_carrito.client_Ref='" . $userFf . "' and client_carrito.isCombo!='Si'";
		$listPrCart = $sql->query($query);

		# this runs all products
		foreach ($listPrCart as $key => $value) {
			$producto = $value['product'];
			$carrito = $value['client_carrito'];
			$precioTotalItem = $producto['prePrecioF'] * $carrito['cantidad'];
			$totalGE += $producto['prePrecioF'] * $carrito['cantidad'];
			$producto['peso'] = str_replace(',', '.', $producto['peso']);
			$peso += (float) $producto['peso'] * $carrito['cantidad'];
			$productos += $carrito['cantidad'];
			if ($producto['impuesto'] == 'iva0') {
				$impuestos += 0;
			} else {
				$impuestos += ($precioTotalItem / 1.16) * (0.16);
			}
			## begin discount block validate
			if ($validDescuento && $descuentoPorcentaje > 0) {
				## exclude
				$validInnerCupon = true;
				foreach ($excludeCategories as $key => $categorie_exclude) {
					$categorie_exclude = $categorie_exclude['product_categorias'];
					if (strpos($producto['preCatego'], $categorie_exclude['code']) !== false) {
						$validInnerCupon = false;
					}
				}

				if ($dataCupon['someCategories'] == 'Si') {
					$validInnerCupon = false;
					foreach ($includeCategories as $key => $categorie_include) {
						$categorie_include = $categorie_include['product_categorias'];
						if (strpos($producto['preCatego'], $categorie_include['code']) !== false) {
							$validInnerCupon = true;
						}
					}
				}
				if ($dataCupon['someProducts'] == 'Si') {
					foreach ($includeProducts as $key => $value) {
						if ($producto['id'] == $value['id']) {
							$validInnerCupon = true;
						}
					}

				}
				if ($validInnerCupon) {
					$descuentoTotalSumado += ($descuentoPorcentaje / 100) * $precioTotalItem;
					$descuentoProductos += ($descuentoPorcentaje / 100) * $precioTotalItem;
				}
			}
			## end discount block validate

			$precioTotalItem = formatcurrency($precioTotalItem);
			$precioItemTemp = formatcurrency($producto['prePrecioF']);

			$listData .= '<tr>
	                      <td width="90">' . $producto['sku'] . '</td>
	                        <td>' . $producto['titulo'] . '</td>
	                        <td>$' . $precioItemTemp . ' COP</td>
	                        <td align="middle">' . $carrito['cantidad'] . '</td>
	                      <td>$' . $precioTotalItem . ' COP</td>
	                    </tr>';

			$carrito1 .= <<< EOF
	            <div class="item ">
	                <img src="{$producto['image_principal']}">
	                <div class="name">{$producto['titulo']}</div>
	                <form class="likeLink" method="post">
	                    <input name="id" value="{$carrito['id']}" type="hidden">
	                    <input name="AG_ACTION" value="deleteCartItem" type="hidden">
	                    <button>Eliminar</button>
	                </form>
	            </div>
EOF;
			$carrito2 .= <<< EOF
	            <div class="item">
	                <img src="{$producto['image_principal']}">
	                <div class="name">{$producto['titulo']}</div>
	                <div class="precioI">{$precioItemTemp}$</div>
	                <div class="ccDf">
	                    <div class="controlsCart clm ">
	                        <div class="less cr update">-</div>
	                        <input value="{$carrito['cantidad']}" data-id="{$carrito['id']}" readonly>
	                        <div class="more cr update">+</div>
	                    </div>
	                    <form class="likeLink" method="post">
	                        <input name="id" value="{$carrito['id']}" type="hidden">
	                        <input name="AG_ACTION" value="deleteCartItem" type="hidden">
	                        <button>Eliminar</button>
	                    </form>
	                </div>
	                <div class="precioF">{$precioTotalItem}$</div>
	            </div>
EOF;
			$carrito3 .= <<< EOF
	            <div class="item ">
	                <img src="{$producto['image_principal']}">
	                <div class="name">{$producto['titulo']}</div>
	                <p>{$carrito['cantidad']}</p>
	            </div>
EOF;
			$carrito4 .= <<< EOF
	                        <div class="item">
	                            <img src="{$producto['image_principal']}">
	                            <div class="name">{$producto['titulo']}</div>
	                            <div class="precioI">{$precioItemTemp}$</div>
	                            <div class="ccDf">
	                                <div class="controlsCart clm ">
	                                    <input value="{$carrito['cantidad']}" data-id="{$carrito['id']}" readonly>
	                                </div>
	                            </div>
	                            <div class="precioF">{$precioTotalItem}$</div>
	                        </div>
EOF;
		}

		$query = "SELECT * FROM client_carrito,product_combos where product_combos.id=client_carrito.producto and client_carrito.client_Ref='" . $userFf . "' and client_carrito.isCombo='Si'";
		$listPrCart = $sql->query($query);

		## combos validate
		foreach ($listPrCart as $key => $value) {
			$precioCombo = 0;
			$precioComboTotal = 0;
			$cantidad = 0;
			$carrito = $value['client_carrito'];
			$combo = $value['product_combos'];
			$varLoop = explode(',', $carrito['productos']);
			$htmlInnerCombo = '';
			$htmlInnerComboMail = '';
			foreach ($varLoop as $key => $value) {
				if ($value !== '') {
					$query = "SELECT * FROM product where product.id='" . $value . "'";
					$proInner = $sql->query($query);
					$producto = $proInner[0]['product'];
					$precioTotalItem = ($producto['prePrecioF'] * $carrito['cantidad']) * (1 - $combo['descuento'] / 100);
					$totalGE += $precioTotalItem;
					$precioCombo += $producto['prePrecioF'] * (1 - $combo['descuento'] / 100);
					$producto['peso'] = str_replace(',', '.', $producto['peso']);
					$peso += (float) $producto['peso'] * $carrito['cantidad'];
					$productos += $carrito['cantidad'];
					$producto['prePrecioF'] = formatcurrency($producto['prePrecioF']);
					$htmlInnerCombo .= <<< EOF
	                <div class="innerItem">
	                    <img src="{$producto['image_principal']}">
	                    <div class="name">{$producto['titulo']}</div>
	                    <div class="precioI">{$producto['prePrecioF']}$</div>
	                </div>
EOF;
					$htmlInnerComboMail .= <<< EOF
	                <tr>
	                    <td width="90"></td>
	                    <td colspan="2">{$producto['titulo']}</td>
	                    <td></td>
	                    <td></td>
	                  </tr>
EOF;
					if ($producto['impuesto'] == 'iva0') {
						$impuestos += 0;
					} else {
						$impuestos += ($precioTotalItem / 1.16) * (0.16);
					}

					## begin discount block validate
					if ($validDescuento && $descuentoPorcentaje > 0) {
						## exclude
						$validInnerCupon = true;
						foreach ($excludeCategories as $key => $categorie_exclude) {
							$categorie_exclude = $categorie_exclude['product_categorias'];
							$categorie_exclude['code'];
							if (strpos($producto['preCatego'], $categorie_exclude['code']) !== false) {
								$validInnerCupon = false;
							}
						}

						if ($dataCupon['someCategories'] == 'Si') {
							$validInnerCupon = false;
							foreach ($includeCategories as $key => $categorie_include) {
								$categorie_include = $categorie_include['product_categorias'];
								if (strpos($producto['preCatego'], $categorie_include['code']) !== false) {
									$validInnerCupon = true;
								}
							}
						}
						if ($dataCupon['someProducts'] == 'Si') {
							foreach ($includeProducts as $key => $value) {
								if ($producto['id'] == $value['id']) {
									$validInnerCupon = true;
								}
							}

						}
						if ($validInnerCupon) {
							$descuentoTotalSumado += ($descuentoPorcentaje / 100) * $precioTotalItem;
							$descuentoProductos += ($descuentoPorcentaje / 100) * $precioTotalItem;
							// d(($descuentoPorcentaje / 100) * $precioTotalItem);
						}
					}
					## end discount block validate
				}

			}
			$combo['prePrecioFT'] = formatcurrency($precioCombo);
			$combo['valorFinal'] = formatcurrency($precioCombo * $carrito['cantidad']);

			$listData .= '<tr>
	                      <td width="90">' . $combo['sku'] . '</td>
	                        <td>' . $combo['titulo'] . '</td>
	                        <td>$' . $combo['prePrecioFT'] . ' COP</td>
	                        <td align="middle">' . $carrito['cantidad'] . '</td>
	                      <td>$' . $combo['valorFinal'] . ' COP</td>
	                    </tr>' . $htmlInnerComboMail;
			$combo['image_principal'] = ag_prImage($combo['image_principal'], '50xauto');
			$carrito1 .= <<< EOF
	                <div class="item i_c_1">
	                    <img src="{$combo['image_principal']}">
	                    <div class="name">{$combo['titulo']}</div>
	                    <form class="likeLink" method="post">
	                        <input name="id" value="{$carrito['id']}" type="hidden">
	                        <input name="AG_ACTION" value="deleteCartItem" type="hidden">
	                        <button>Eliminar</button>
	                    </form>
	                </div>
EOF;
			$carrito2 .= <<< EOF
	            <div class="item itemCombo i_c_2">
	                <img src="{$combo['image_principal']}">
	                <div class="name">{$combo['titulo']}</div>
	                <div class="precioI">{$combo['prePrecioFT']}$</div>
	                <div class="ccDf">
	                    <div class="controlsCart clm ">
	                        <div class="less cr update">-</div>
	                        <input value="{$carrito['cantidad']}" data-id="{$carrito['id']}" readonly>
	                        <div class="more cr update">+</div>
	                    </div>
	                    <form class="likeLink" method="post">
	                        <input name="id" value="{$carrito['id']}" type="hidden">
	                        <input name="AG_ACTION" value="deleteCartItem" type="hidden">
	                        <button>Eliminar</button>
	                    </form>
	                </div>
	                <div class="precioF">{$combo['valorFinal']}$</div>
	                {$htmlInnerCombo}
	            </div>
EOF;
			$carrito3 .= <<< EOF
	            <div class="item i_c_3">
	                <img src="{$combo['image_principal']}">
	                <div class="name">{$combo['titulo']}</div>
	                <p>{$carrito['cantidad']}</p>
	            </div>
EOF;
			$carrito4 .= <<< EOF
	            <div class="item itemCombo i_c_4">
	                <img src="{$combo['image_principal']}">
	                <div class="name">{$combo['titulo']}</div>
	                <div class="precioI">{$combo['prePrecioFT']}$</div>
	                <div class="ccDf">
	                    <div class="controlsCart clm ">
	                        <input value="{$carrito['cantidad']}" data-id="{$carrito['id']}" readonly>
	                    </div>
	                </div>
	                <div class="precioF">{$combo['valorFinal']}$</div>
	                {$htmlInnerCombo}
	            </div>
EOF;
		}

		$isCeroPeso = false;
		$pesoInt = (int) $peso;

		$peso = $peso > $pesoInt ? $pesoInt + 1 : $pesoInt;
		if ($peso === 0) {
			$isCeroPeso = true;
		}

		$subTotal = $totalGE - $impuestos;
		// $impuestos = ($totalGE / 1.16) * 0.16;
		$boolBogota = false;
		$noDireccion = false;
		if (isset($AG_USER["direccion"])) {
			$query = "SELECT * FROM client_direcciones WHERE id = " . $AG_USER["direccion"];
			$info = $sql->query($query);
			if ($info) {
				$direccion = $info[0]['client_direcciones'];
				// todo check if im in bogota when not loged of not city
				if (strtoupper($direccion['ciudad']) == strtoupper('bogota - cundinamarca')) {
					$boolBogota = true;
				}
				$noDireccion = true;
			}

		}
		if ($noDireccion) {
			if ($boolBogota) {
				if ($totalGE >= 100000) {
					$envio = 0;
				} else if ($totalGE >= 50000) {
					$envio = 5000;
				} else {
					$envio = 7500;
				}

			} else {
				// if($peso==1)
				//     $envio  = 6800;
				// else{
				//     $envio  = 6800 + (2500*($peso-1));
				// }
				$envio = 6800 + (2500 * ($peso - 1));
				// $envio =$peso;
			}
			$data['envio'] = $envio;
			$data['envioSt'] = '$ ' . formatcurrency($envio);
		} else {
			$data['envioSt'] = ' - ';
		}

		## check si tiene precio minimo
		if ($precioMinino > 0) {
			if ($totalGE < $precioMinino) {
				$validDescuento = false;
			}
		}
		## descuento global
		if ($validDescuento) {
			if ($descuentValor > 0) {
				$descuentoTotalSumado += $descuentValor;
				$descuentoProductos += $descuentValor;
			}
			if ($descuentoEnvio > 0) {
				$descuentoTotalSumado += ($descuentoEnvio / 100) * $envio;
				$descuentoEnvioPesos += ($descuentoEnvio / 100) * $envio;

			}
		}

		if ($isCeroPeso) {
			$envio = 0;
			$data['envio'] = 0;
			$data['envioSt'] = '-';
		}

		$data['descuento'] = $descuentoTotalSumado;
		$data['descuentoSt'] = formatcurrency($data['descuento']);

		$data['number_products'] = $productos;

		$data['listData'] = $listData;

		$data['peso'] = $peso;
		$data['impuestos'] = $impuestos;
		$data['impuestosStr'] = formatcurrency($impuestos);
		$data['subTotal'] = $subTotal;
		$data['subTotalStr'] = formatcurrency($subTotal);
		$data['total'] = $totalGE;
		$data['totalSt'] = formatcurrency($totalGE);
		$data['final'] = $totalGE - $descuentoProductos;
		$data['final'] = $data['final'] < 0 ? 0 : $data['final'];
		$data['final'] = $data['final'] + $envio - $descuentoEnvioPesos;
		if ($data['final'] < 0) {
			$data['final'] = 0;
		}

		$data['finalSt'] = formatcurrency($data['final']);

		## final info carts for html ws info carts
		$carrito1 .= '</div>
	    <div class="orderNumers">
	        <label>Sub Total </label><span>$' . $data['totalSt'] . '</span><div class="hr"></div>
	        <label class="pege">Precio sin Iva </label><span class="pege">$' . $data['subTotalStr'] . '</span><div class="hr"></div>
	        <label class="pege">Iva </label><span class="pege">$' . $data['impuestosStr'] . '</span><div class="hr"></div>
	        <label>Descuentos </label><span>- $' . $data['descuentoSt'] . '</span><div class="hr"></div>
	        <label>Envio</label><span>' . $data['envioSt'] . ' </span><div class="hr"></div>
	        <label class="bold blue">Total </label><span class="bold blue">$' . $data['finalSt'] . '</span>
	    </div>';

		$carrito5 .= '<div class="orderSumary">
	                <div class="title">
	                    Resumen Pedido
	                </div>
	                </div>
	                <div class="orderNumers">
	                     <label>Sub Total </label><span>$' . $data['totalSt'] . '</span><div class="hr"></div>
	                    <label class="pege">Precio sin Iva </label><span class="pege">$' . $data['subTotalStr'] . '</span><div class="hr"></div>
	                    <label class="pege">Iva </label><span class="pege">$' . $data['impuestosStr'] . '</span><div class="hr"></div>
	                    <label>Descuentos </label><span>- $' . $data['descuentoSt'] . '</span><div class="hr"></div>
	                    <label>Envio</label><span>' . $data['envioSt'] . ' </span><div class="hr"></div>
	                    <label class="bold blue">Total </label><span class="bold blue">$' . $data['finalSt'] . '</span>
	                </div>';

		if ($data['number_products'] === 0) {
			$carrito2 = '
	        <div class="itemTitle">

	        </div>';
		}

		$data['carrito1'] = $carrito1;
		$data['carrito2'] = $carrito2; ## big cart
		$data['carrito3'] = $carrito3; # inner cart  /shopingCartFix
		$data['carrito4'] = $carrito4;
		$data['carrito5'] = $carrito5;

		return $data;
	}
}

?>