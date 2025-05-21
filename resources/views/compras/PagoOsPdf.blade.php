<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Orden de Compra</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
            color: #336699; 
            background-color: #ffffff;
            border: 1px solid #5B92D9; 
        }

        thead th {
            background-color: #336699; 
            color: #ffffff; 
            border: 1px solid #5B92D9;
            padding: 8px;
            text-align: center;
        }

        tbody td {
            border: 1px solid #5B92D9;
            padding: 8px;
            text-align: center;
            color: #000000;
            background-color: #ffffff; 
        }

        tbody tr:nth-child(odd) td {
            background-color: #EBF0FF; 
        }

        tbody tr:nth-child(even) td {
            background-color: #FFFFFF;
        }
        .nota-importante {
            margin-top: 10px;
            font-size: 10pt;
            color: #000;
            text-align: left;
            line-height: 1.4;
            padding-top: 15px;
            width: 100%;
        }
        .nota-importante strong {
            display: block;
            margin-bottom: 10px;
        }
        .nota-importante ul {
            list-style-type: disc;
            margin-left: 20px;
            padding-left: 0;
        }
    </style>
</head>
<body>
    @php
        $cabecera = $detalle[0];
    @endphp
    <div style="display: table; width: 100%; margin-bottom: 1rem;">
        <div style="display: table-cell; vertical-align: middle; width: 130px;">
            <img src="{{ public_path('img/logoCorp.png') }}" alt="Logo" style="width: 120px; height: auto;">
        </div>
        <div style="display: table-cell; vertical-align: middle; padding-left: 10px;">
            <div style="text-align: center;">
                <h1 style="font-size: 1.5rem; font-weight: bold; margin: 0;">CORPORACIÓN ARZOBISPO LOAYZA S.A.C.</h1>
                <p style="margin: 2px 0;"><strong>RUC:</strong> {{ $cabecera->RUCEmp ?? 'Ruc no disponible' }}</p>
                <p style="margin: 2px 0;">PJE. NUEVA ROSITA NRO. 140 CERCADO DE LIMA -LIMA</p>
                <p style="margin: 2px 0;"><strong>ORDEN DE COMPRA N°: </strong>{{ $cabecera->Transaccion ?? 'Transaccion no disponible' }}</p>
            </div>
        </div>
    </div>

    <div style="display: table; width: 100%; font-size: 11pt; margin-top: 30px; border-spacing: 0 10px;">
        <div style="display: table-row;">
            <div style="display: table-cell; width: 150px; font-weight: bold; vertical-align: top;">PROVEEDOR:</div>
            <div style="display: table-cell; vertical-align: top;">
                {{ $cabecera->RazonSocial ?? 'Razon Social no disponible' }}
            </div>
        </div>
        <div style="display: table-row;">
            <div style="display: table-cell; width: 150px; font-weight: bold; vertical-align: top;">RUC:</div>
            <div style="display: table-cell; vertical-align: top;">{{ $cabecera->RUC ?? 'Ruc no disponible' }}</div>
        </div>
        <div style="display: table-row;">
            <div style="display: table-cell; width: 150px; font-weight: bold; vertical-align: top;">DIRECCIÓN:</div>
            <div style="display: table-cell; vertical-align: top; white-space: pre-line;">{{ $cabecera->Direccion ?? 'Direccion no disponible' }}</div>
        </div>
        <div style="display: table-row;">
            <div style="display: table-cell; width: 150px; font-weight: bold; vertical-align: top;">FECHA DE PEDIDO:</div>
            <div style="display: table-cell; vertical-align: top;">{{ $cabecera->Fecha ?? 'Fecha no disponible' }}</div>
        </div>
        <div style="display: table-row;">
            <div style="display: table-cell; width: 150px; font-weight: bold; vertical-align: top;">FECHA DE PAGO:</div>
            <div style="display: table-cell; vertical-align: top;">{{ $cabecera->FechaPago ?? 'Fecha Pago no disponible' }}</div>
        </div>
        <div style="display: table-row;">
            <div style="display: table-cell; width: 150px; font-weight: bold; vertical-align: top;">FORMA DE PAGO:</div>
            <div style="display: table-cell; vertical-align: top;">{{ strtoupper($cabecera->FormaPago) ?? 'Forma Pago no disponible' }}</div>
        </div>
        <div style="display: table-row;">
            <div style="display: table-cell; width: 150px; font-weight: bold; vertical-align: top;">LOCAL:</div>
            <div style="display: table-cell; vertical-align: top;">{{ strtoupper($cabecera->Sede) ?? 'Sede no disponible' }}</div>
        </div>
        <div style="display: table-row;">
            <div style="display: table-cell; width: 150px; font-weight: bold; vertical-align: top;">MONEDA:</div>
            <div style="display: table-cell; vertical-align: top;">{{ strtoupper($cabecera->Moneda) ?? 'Moneda no disponible' }}</div>
        </div>
        <div style="display: table-row;">
            <div style="display: table-cell; width: 150px; font-weight: bold; vertical-align: top;">AUTORIZADO:</div>
            <div style="display: table-cell; vertical-align: top;">{{ strtoupper($cabecera->Autorizado) ?? 'Autorizado no disponible' }}</div>
        </div>
        <div style="display: table-row;">
            <div style="display: table-cell; width: 150px; font-weight: bold; vertical-align: top;">SOLICITADO:</div>
            <div style="display: table-cell; vertical-align: top;">{{ strtoupper($cabecera->Solicitado) ?? 'Solicitado no disponible' }}</div>
        </div>
    </div>

    <div>
        <p>SUMINISTRAR LOS SIGUIENTES ARTICULOS:</p>
        <table>
            <thead>
                <tr>
                    <th style="text-align: center;">Ítem</th>
                    <th style="text-align: center;">IDL</th>
                    <th style="text-align: center;">Op</th>
                    <th style="text-align: center;">Descripción</th>
                    <th style="text-align: center;">Cantidad</th>
                    <th style="text-align: center;">Precio</th>
                    <th style="text-align: center;">Importe</th>
                </tr>
            </thead>
            <tbody>
                @php $contador = 1; @endphp
                @foreach($tabla as $item)
                    <tr>
                        <td style="text-align: center;">{{ $contador++ }}</td>
                        <td style="text-align: center;">{{ $item->IDL }}</td>
                        <td style="text-align: center;">{{ $item->Op }}</td>
                        <td style="text-align: center;">{{ strtoupper($item->Descripcion) ?? '' }}</td>
                        <td style="text-align: center;">{{ $item->Cantidad ?? '0' }}</td>
                        <td style="text-align: center;">{{ number_format($item->Precio, 2) ?? '0.00' }}</td>
                        <td style="text-align: center;">{{ number_format($item->Importe, 2) ?? '0.00' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="text-align: right; margin-top: 20px;font-family: monospace;">
        <div>
            <span style="display: inline-block; width: 300px; text-align: left;">TOTAL</span>
            <span>{{ number_format($cabecera->PSubtoTal ?? 0, 2) }}</span>
        </div>
        <div>
            <span style="display: inline-block; width: 320px; text-align: left; color: red;">%DETRACCIÓN ({{ $cabecera->Porcentaje ?? 0 }})
            </span>
            <span>{{ number_format($cabecera->Detraccion ?? 0, 2) }}</span>
        </div>
        <div>
            <span style="display: inline-block; width: 300px; text-align: left;">TOTAL A PAGAR</span>
            <span>{{ number_format($cabecera->Total2 ?? 0, 2) }}</span>
        </div>
    </div>

    <div style="margin-top: 100px; text-align: center;">
        <div style="display: inline-block; width: 32%; vertical-align: top; margin-right: 1%;">
            <div>------------------------------</div>
            <div>V° B° Logística Sadith Fernandez Linares</div>
        </div>
        <div style="display: inline-block; width: 32%; vertical-align: top; margin-right: 1%;">
            <div>------------------------------</div>
            <div>B° Gerente General</div>
        </div>
        <div style="display: inline-block; width: 32%; vertical-align: top;">
            <div>------------------------------</div>
            <div>V° B° Almacenero</div>
        </div>
    </div>

    <div class="nota-importante">
        <strong>NOTA IMPORTANTE:</strong>
        <ul>
            <li>El Proceso debe adjuntar a su factura copia de O/C atendida</li>
            <li>Esta Orden es nula sin las firmas y sellos autorizados</li>
            <li>Nos reservamos el derecho de devolver la mercancia que no este de acuerdo con las especificaciones tecnicas</li>
            <li>Todo pago debe salir del titular de la Cta Cte y/o efectivo</li>
            <li>Corporacion Arzobispo Loayza SAC. es agente Retenedor designado por SUNAT</li>
        </ul>
    </div>
    
</body>

</html>
