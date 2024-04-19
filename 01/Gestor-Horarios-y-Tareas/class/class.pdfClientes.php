<?php 


class PDFClientes extends FPDF
{
    // Encabezado
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'GESBANK 1.0', 0, 0, 'L');
        $this->Cell(0, 10, 'Pablo Mateos Palas', 0, 0, 'C');
        $this->Cell(0, 10, '2DAW 23/24', 0, 1, 'R');
        $this->Cell(0, 0, '', 'T', 1, 'L'); // Borde inferior
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        $this->Cell(0, 0, '', 'T', 1, 'L'); // Borde superior
    }

    // Título del informe
    function TituloInforme()
    {
        $this->Ln(10); // Espacio entre encabezado y título
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Informe: Listado de Cliente', 0, 1, 'L');
        $this->Cell(0, 10, 'Fecha: ' . date('Y-m-d H:i:s'), 0, 1, 'L');
    }

    // Encabezado del listado
    function EncabezadoListado($columnas)
    {
        $this->SetFillColor(200, 220, 255); // Color de fondo
        $this->SetFont('Arial', 'B', 10);
        
        foreach ($columnas as $col) {
            $this->Cell($col['width'], 10, $col['header'], 1, 0, 'C', true); // Borde inferior y fondo
        }
        $this->Ln();
    }

    // Contenido del informe
    function ContenidoListado($data, $columnas)
    {
        $this->SetFont('Arial', '', 10);

        foreach ($data as $row) {
            foreach ($columnas as $col) {
                $this->Cell($col['width'], 10, $row[$col['field']], 1);
            }
            $this->Ln();
        }
    }
}
