import {
  HomeIcon,
  Bars3Icon,
  XMarkIcon,
  AcademicCapIcon,
  PrinterIcon,
  DocumentDuplicateIcon,
  DocumentIcon,
} from "@heroicons/react/24/outline";

const navigationData = [
  {
    name: "Produk",
    href: "",
    subnav: [
      {
        name: "Peralatan Sekolah",
        description: "Alat-alat yang diperlukan untuk kegiatan sekolah.",
        href: "#",
        icon: AcademicCapIcon,
      },
      {
        name: "Peralatan Rumah Tangga",
        description: "Berbagai macam peralatan untuk kebutuhan rumah tangga.",
        href: "#",
        icon: HomeIcon,
      },
    ],
  },
  {
    name: "PPOB",
    href: "#",
  },
  {
    name: "Jasa Printing",
    subnav: [
      {
        name: "Cetak Foto",
        description: "Layanan untuk mencetak foto dengan kualitas tinggi.",
        href: "#",
        icon: PrinterIcon,
      },
      {
        name: "Membuat Salinan Dokumen",
        description: "Layanan untuk membuat salinan dokumen penting.",
        href: "#",
        icon: DocumentDuplicateIcon,
      },
      {
        name: "Scan Dokumen",
        description: "Layanan untuk melakukan scan dokumen.",
        href: "#",
        icon: DocumentIcon,
      },
      {
        name: "Membuat Sertifikat",
        description: "Layanan untuk membuat sertifikat resmi.",
        href: "#",
        icon: PrinterIcon,
      },
    ],
    href: "#",
  },
  { name: "Marketplace", href: "#" },
  { name: "Company", href: "#" },
  { name: "Peralatan", href: "#" },
];

export default navigationData;
