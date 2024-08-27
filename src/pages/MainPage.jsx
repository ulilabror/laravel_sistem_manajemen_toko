"use client";

import Layout from "../components/layouts/Layout";
import ScrollAnimation from 'react-animate-on-scroll';

import { Link } from "react-router-dom";
import "animate.css/animate.compat.css"

export const MainPage = () => {
  return (
    <>
      <Layout>

        <section>
          <div className="relative isolate px-6 pt-14 lg:px-8 bg-[#CCE0AC] dark:bg-[#002029]">
            <ScrollAnimation initiallyVisible={true} animateIn='bounceInRight'>
              <div className="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div className="text-center">
                  <h1 className="text-4xl font-bold tracking-tight text-black sm:text-6xl dark:text-gray-100">
                    Peralatan Sekolah & Rumah
                  </h1>
                  <p className="mt-6 text-lg leading-8 text-gray-800 dark:text-gray-100">
                    Kami menyediakan beragam peralatan sekolah dan rumah tangga berkualitas tinggi untuk memenuhi kebutuhan Anda sehari-hari. Dapatkan produk-produk terbaik dengan harga yang kompetitif.
                  </p>
                  <div className="mt-10 flex items-center justify-center gap-x-6">
                    <Link
                      to="/products"
                      className="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                      Lihat Produk
                    </Link>
                  </div>
                </div>
              </div>
            </ScrollAnimation>
          </div>
        </section>

        <section>
          <div className="relative isolate px-6 pt-14 lg:px-8 bg-[#F4DEB3] dark:bg-[#00303d]">
            <ScrollAnimation animateIn='bounceInRight'>

              <div className="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div className="text-center">
                  <h1 className="text-4xl font-bold tracking-tight text-black sm:text-6xl dark:text-gray-100">
                    Jasa Isi Pulsa
                  </h1>
                  <p className="mt-6 text-lg leading-8 text-gray-800 dark:text-gray-100">
                    Nikmati layanan isi ulang pulsa yang cepat, aman, dan terpercaya. Kami menjamin transaksi Anda berjalan lancar dan saldo pulsa Anda langsung bertambah.
                  </p>
                  <div className="mt-10 flex items-center justify-center gap-x-6">
                    <Link
                      to="/pulsa"
                      className="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                      Isi Pulsa
                    </Link>
                  </div>
                </div>
              </div>
            </ScrollAnimation>
          </div>
        </section>

        <section>
          <div className="relative isolate px-6 pt-14 lg:px-8 bg-[#F0EAAC] dark:bg-[#004052]">
            <ScrollAnimation animateIn='bounceInRight'>

              <div className="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div className="text-center">
                  <h1 className="text-4xl font-bold tracking-tight text-black sm:text-6xl dark:text-gray-100">
                    Jasa Isi Kuota
                  </h1>
                  <p className="mt-6 text-lg leading-8 text-gray-800 dark:text-gray-100">
                    Dapatkan kuota internet Anda dengan mudah dan cepat melalui layanan kami. Kami bekerja sama dengan berbagai provider untuk memberikan kemudahan bagi pelanggan.
                  </p>
                  <div className="mt-10 flex items-center justify-center gap-x-6">
                    <Link
                      to="/kuota"
                      className="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                      Isi Kuota
                    </Link>
                  </div>
                </div>
              </div>
            </ScrollAnimation>
          </div>
        </section>

        <section>
          <div className="relative isolate px-6 pt-14 lg:px-8 bg-[#CCE0AC] dark:bg-[#005066]">
            <ScrollAnimation animateIn='bounceInRight'>

              <div className="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div className="text-center">
                  <h1 className="text-4xl font-bold tracking-tight text-black sm:text-6xl dark:text-gray-100">
                    Topup Game
                  </h1>
                  <p className="mt-6 text-lg leading-8 text-gray-800 dark:text-gray-100">
                    Tingkatkan pengalaman bermain game Anda dengan layanan top up game dari kami. Transaksi aman dan cepat, sehingga Anda bisa langsung melanjutkan permainan.
                  </p>
                  <div className="mt-10 flex items-center justify-center gap-x-6">
                    <Link
                      to="/topup"
                      className="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                      Topup Sekarang
                    </Link>
                  </div>
                </div>
              </div>
            </ScrollAnimation>
          </div>
        </section>

        <section>
          <div className="relative isolate px-6 pt-14 lg:px-8 bg-gray-50 dark:bg-[#00607a]">
            <ScrollAnimation animateIn='bounceInRight'>

              <div className="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div className="text-center">
                  <h1 className="text-4xl font-bold tracking-tight text-black sm:text-6xl dark:text-gray-100">
                    Jasa Printing
                  </h1>
                  <p className="mt-6 text-lg leading-8 text-gray-800 dark:text-gray-100">
                    Percayakan kebutuhan printing Anda kepada kami. Kami menawarkan layanan printing dengan kualitas terbaik dan harga yang terjangkau.
                  </p>
                  <div className="mt-10 flex items-center justify-center gap-x-6">
                    <Link
                      to="/printing"
                      className="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                      Cetak Sekarang
                    </Link>
                  </div>
                </div>
              </div>
            </ScrollAnimation>
          </div>
        </section>
      </Layout>

    </>
  );
};

export default MainPage;
