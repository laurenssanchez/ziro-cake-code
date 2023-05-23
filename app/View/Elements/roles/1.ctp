<div class="menu_section">
    <h3>General</h3>
    <ul class="nav side-menu">
        <li>
            <a href="<?php echo $this->Html->url(["controller" => "pages", "action" => "dashboard", "?" => ["dateIni" => date("Y-m-d", strtotime("-6 month")), "dateEnd" => date("Y-m-d")]]) ?>">
                <i class="fa fa-list"></i>
                Dashboard <span class="fa fa-list"></span>
            </a>
        </li>
        <li>
            <a>
                <i class="fa fa-file"></i>
                Informes <span class="fa fa-file"></span>
            </a>
            <ul class="nav child_menu">
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "credits", "action" => "intereses", "?" => ["tab" => 1]]) ?>">
                        Intereses
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "credits", "action" => "recaudos"]) ?>">
                        Recaudos
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "credits", "action" => "cartera", "?" => ["tab" => 1]]) ?>">
                        Cartera
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "credits", "action" => "centrales"]) ?>">
                        Reporte centrales
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "credits", "action" => "juridico"]) ?>">
                        Informe jurídico
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "shop_payment_requests", "action" => "informe"]) ?>">
                        Informe saldos y desembolsos
                    </a>
                </li>
				<li>
                    <a href="<?php echo $this->Html->url(["controller" => "credits", "action" => "cupos"]) ?>">
                        Cupos
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?php echo $this->Html->url(["controller" => "customers_codes", "action" => "index", "?" => ["tab" => 1]]) ?>">
                <i class="fa fa-phone"></i>
                Códigos enviados <span class="fa fa-phone"></span>
            </a>
        </li>
        <li>
            <a>
                <i class="fa fa-shopping-basket"></i>
                Solicitudes de cupos <span class="fa fa-money"></span>
            </a>
            <ul class="nav child_menu">
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index", "?" => ["usoFecha" => "1", "ccCustomer" => "", "commerce" => "", "ini" => date("Y-m-d", strtotime("-1 day")), "end" => date("Y-m-d")]]) ?>">
                        Por Tarjetas
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "index_lista"]) ?>">
                        Por Lista
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?php echo $this->Html->url(["controller" => "users", "action" => "index"]) ?>">
                <i class="fa fa-user"></i>
                Usuarios del sistema <span class="fa fa-user"></span>
            </a>
        </li>
		<li>
            <a href="<?php echo $this->Html->url(["controller" => "customers", "action" => "index"]) ?>">
                <i class="fa fa-users"></i>
                Clientes <span class="fa fa-user"></span>
            </a>
        </li>
        <!--<li>-->
        <!--  <a href="<?php echo $this->Html->url(["controller" => "empresas", "action" => "index"]) ?>">-->
        <!--    <i class="fa fa-user"></i>-->
        <!--    Empresas <span class="fa fa-user"></span>-->
        <!--  </a>-->
        <!--</li>-->
        <li>
            <a>
                <i class="fa fa-shopping-basket"></i>
                Proveedores aliados <span class="fa fa-connectdevelop"></span>
            </a>
            <ul class="nav child_menu">
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "shops", "action" => "index"]) ?>">Listar proveedores
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "shops", "action" => "add"]) ?>">
                        Crear proveedor
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a>
                <i class="fa fa-object-group"></i>
                Líneas de créditos <span class="fa fa-connectdevelop"></span>
            </a>
            <ul class="nav child_menu">
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "credits_lines", "action" => "index"]) ?>">Listar líneas
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->Html->url(["controller" => "credits_lines", "action" => "add"]) ?>">
                        Crear línea
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?php echo $this->Html->url(["controller" => "shop_payment_requests", "action" => "index", "?" => ["tab" => 1]]) ?>">
                <i class="fa fa-money"></i>
                Saldos y desembolsos<span class="fa fa-money"></span>
            </a>
        </li>
        <li>
            <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "cobranza", "?" => ["tab" => 1]]) ?>">
                <i class="fa fa-money"></i>
                Gestión de Cobranza <span class="fa fa-money"></span>
            </a>
        </li>

        <li>
            <a href="<?php echo $this->Html->url(["controller" => "credits_requests", "action" => "juridico", "?" => ["tab" => 1]]) ?>">
                <i class="fa fa-money"></i>
                Gestión Jurídica <span class="fa fa-money"></span>
            </a>
        </li>

        <li>
            <a href="<?php echo $this->Html->url(["controller" => "payments", "action" => "index"]) ?>">
                <i class="fa fa-money"></i>
                Recaudos <span class="fa fa-money"></span>
            </a>
        </li>
        <!--<li>-->
        <!--  <a href="<?php echo $this->Html->url(["controller" => "requests_payments", "action" => "index"]) ?>">-->
        <!--    <i class="fa fa-money"></i>-->
        <!--    Transacciones <span class="fa fa-money"></span>-->
        <!--  </a>-->
        <!--</li>-->
        <li>
            <a href="<?php echo $this->Html->url(["controller" => "configs", "action" => "edit"]) ?>">
                <i class="fa fa-money"></i>
                Configuración <span class="fa fa-money"></span>
            </a>
        </li>
        <li>
            <a href="<?php echo $this->Html->url(["controller" => "simulators", "action" => "index"]) ?>">
                <i class="fa fa-money"></i>
                Simuladores <span class="fa fa-money"></span>
            </a>
        </li>
        <li>
            <a href="<?php echo $this->Html->url(["controller" => "automatics", "action" => "index"]) ?>">
                <i class="fa fa-money"></i>
                Config automatica <span class="fa fa-money"></span>
            </a>
        </li>
		<li>
			<a href="<?php echo $this->Html->url(["controller" => "signatures", "action" => "edit"]) ?>">
				<i class="fa fa-list"></i>
				Config firmas <span class="fa fa-list"></span>
			</a>
    	</li>
    </ul>
</div>
