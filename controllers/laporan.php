<?php
require_once '../tcpdf/vendor/tecnickcom/tcpdf/tcpdf.php';
require_once '../config/database.php';

class ModernPDF extends TCPDF {
    protected $headerTitle = 'LAPORAN TRANSAKSI';
    protected $headerSubtitle = 'Sistem Manajemen Kontrakan';

    public function Header() {
        // Background header rectangle
        $this->Rect(0, 0, $this->getPageWidth(), 40, 'F', array(), array(41, 128, 185));
        
        // Logo
        $image_file = '../assets/images/logo.jpg';
        if(file_exists($image_file)) {
            $this->Image($image_file, 15, 10, 25, '', 'jpg');
        }
        
        // White text for header
        $this->SetTextColor(255, 255, 255);
        
        // Title
        $this->SetFont('helvetica', 'B', 24);
        $this->SetXY(45, 15);
        $this->Cell(0, 10, $this->headerTitle, 0, 1, 'L');
        
        // Subtitle
        $this->SetFont('helvetica', '', 12);
        $this->SetXY(45, 25);
        $this->Cell(0, 10, $this->headerSubtitle, 0, 1, 'L');
        
        // Reset text color
        $this->SetTextColor(0, 0, 0);
    }

    public function Footer() {
        // Footer rectangle
        $this->Rect(0, $this->getPageHeight() - 25, $this->getPageWidth(), 25, 'F', array(), array(245, 247, 250));
        
        $this->SetY(-20);
        $this->SetFont('helvetica', '', 8);
        
        // Left side - Page numbers
        $this->SetX(15);
        $this->Cell(0, 10, 'Halaman '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, 0, 'L');
        
        // Center - Company name
        $this->SetX(($this->getPageWidth() - 100) / 2);
        $this->Cell(100, 10, 'Sistem Manajemen Kontrakan © '.date('Y'), 0, 0, 'C');
        
        // Right side - Date
        $this->SetX(-80);
        $this->Cell(65, 10, 'Dicetak: ' . date('d-m-Y H:i'), 0, 0, 'R');
    }
}

// Get date filter from POST
$tanggal_mulai = $_POST['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'];

// Enhanced query with payment calculations
$query = "SELECT 
            penyewa.nama AS penyewa, 
            kontrakan.nama_kontrakan, 
            kamar.nomor_kamar, 
            kamar.harga_sewa, 
            transaksi.tanggal_sewa, 
            transaksi.jumlah_bayar AS total,
            (kamar.harga_sewa - transaksi.jumlah_bayar) AS sisa_bayar
          FROM transaksi
          JOIN penyewa ON transaksi.id_penyewa = penyewa.id_penyewa
          JOIN kamar ON transaksi.id_kamar = kamar.id_kamar
          JOIN kontrakan ON kamar.id_kontrakan = kontrakan.id_kontrakan
          WHERE transaksi.tanggal_sewa BETWEEN ? AND ?
          ORDER BY transaksi.tanggal_sewa ASC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $tanggal_mulai, $tanggal_selesai);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Initialize PDF
$pdf = new ModernPDF('L', 'mm', 'A4');
$pdf->SetCreator('Sistem Manajemen Kontrakan');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Laporan Transaksi');

// Set margins and breaks
$pdf->SetMargins(15, 50, 15);
$pdf->SetHeaderMargin(20);
$pdf->SetFooterMargin(25);
$pdf->SetAutoPageBreak(TRUE, 30);

// Add page
$pdf->AddPage();

// Period information with modern styling
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetFillColor(41, 128, 185, 0.1);
$pdf->SetTextColor(41, 128, 185);
$pdf->Cell(0, 12, 'Periode: ' . date('d F Y', strtotime($tanggal_mulai)) . ' s/d ' . date('d F Y', strtotime($tanggal_selesai)), 0, 1, 'C', true);
$pdf->Ln(5);

// Modern table styling
// Header
$pdf->SetFillColor(52, 152, 219); // Modern blue
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetLineWidth(0.1);

$pdf->Cell(45, 10, 'Nama Penyewa', 1, 0, 'C', true);
$pdf->Cell(45, 10, 'Nama Kontrakan', 1, 0, 'C', true);
$pdf->Cell(25, 10, 'No. Kamar', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Harga Sewa', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Jumlah Bayar', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Sisa Bayar', 1, 1, 'C', true);

// Reset styles for data
$pdf->SetTextColor(73, 80, 87);
$pdf->SetFont('helvetica', '', 10);

// Initialize totals
$total_harga_sewa = 0;
$total_bayar = 0;
$total_sisa = 0;

// Data rows with modern alternating colors
$fill = false;
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->SetFillColor(236, 240, 245);
    
    $pdf->Cell(45, 8, $row['penyewa'], 1, 0, 'L', $fill);
    $pdf->Cell(45, 8, $row['nama_kontrakan'], 1, 0, 'L', $fill);
    $pdf->Cell(25, 8, $row['nomor_kamar'], 1, 0, 'C', $fill);
    $pdf->Cell(40, 8, 'Rp ' . number_format($row['harga_sewa'], 0, ',', '.'), 1, 0, 'R', $fill);
    $pdf->Cell(35, 8, date('d/m/Y', strtotime($row['tanggal_sewa'])), 1, 0, 'C', $fill);
    $pdf->Cell(40, 8, 'Rp ' . number_format($row['total'], 0, ',', '.'), 1, 0, 'R', $fill);
    
    // Add color coding for sisa bayar
    $sisa_style = $row['sisa_bayar'] > 0 ? array(231, 76, 60) : array(46, 204, 113);
    $pdf->SetTextColor($sisa_style[0], $sisa_style[1], $sisa_style[2]);
    $pdf->Cell(35, 8, 'Rp ' . number_format(abs($row['sisa_bayar']), 0, ',', '.'), 1, 1, 'R', $fill);
    $pdf->SetTextColor(73, 80, 87);
    
    // Update totals
    $total_harga_sewa += $row['harga_sewa'];
    $total_bayar += $row['total'];
    $total_sisa += $row['sisa_bayar'];
    
    $fill = !$fill;
}

// Summary section with modern styling
$pdf->Ln(5);
$pdf->SetFillColor(52, 152, 219);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 11);

// Total row
$pdf->Cell(115, 10, 'TOTAL', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Rp ' . number_format($total_harga_sewa, 0, ',', '.'), 1, 0, 'R', true);
$pdf->Cell(35, 10, '', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Rp ' . number_format($total_bayar, 0, ',', '.'), 1, 0, 'R', true);
$pdf->Cell(35, 10, 'Rp ' . number_format(abs($total_sisa), 0, ',', '.'), 1, 1, 'R', true);

// Statistics section
$pdf->Ln(10);
$pdf->SetTextColor(73, 80, 87);
$pdf->SetFillColor(241, 246, 251);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'RINGKASAN TRANSAKSI', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

// Modern statistics boxes
$stats = array(
    array('Total Transaksi', mysqli_num_rows($result) . ' transaksi'),
    array('Total Harga Sewa', 'Rp ' . number_format($total_harga_sewa, 0, ',', '.')),
    array('Total Pembayaran', 'Rp ' . number_format($total_bayar, 0, ',', '.')),
    array('Total Sisa Pembayaran', 'Rp ' . number_format(abs($total_sisa), 0, ',', '.'))
);

foreach ($stats as $stat) {
    $pdf->SetFillColor(241, 246, 251);
    $pdf->Cell(65, 12, $stat[0], 1, 0, 'L', true);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Cell(100, 12, $stat[1], 1, 1, 'R', true);
}

// Signature section with modern styling
$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 10, 'Mengetahui,', 0, 1, 'R');
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 10, 'Admin Kontrakan', 0, 1, 'R');
$pdf->Ln(15);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 10, '(_____________________)', 0, 1, 'R');

// Output PDF
$pdf->Output('Laporan_Transaksi_' . date('Ymd') . '.pdf', 'I');
?>