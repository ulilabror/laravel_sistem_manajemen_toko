import React from 'react';
import { useParams } from 'react-router-dom';
import Layout from "../../components/layouts/Layout";
import ProductCategory from "../../components/product/ProductCategory";
import ProductDetail from "../../components/product/ProductDetail";
import Section from '../../components/common/Section';
import Container from '../../components/layouts/Container';

export default function ProductDetailPage({ products }) {
    const { id } = useParams(); // Ambil ID dari URL
    const product = products.find((item) => item.product_barcode_id === parseInt(id)); // Cari produk berdasarkan ID

    if (!product) {
        return (
            <Layout>
                <Section>

                    <div className="text-center">
                        <h2 className="text-2xl font-bold text-gray-700 dark:text-gray-300">
                            Product not found
                        </h2>
                        <p className="mt-4 text-gray-500 dark:text-gray-400">
                            The product you are looking for does not exist or has been removed.
                        </p>
                    </div>
                </Section>

            </Layout>
        )
    }



    return (
        <Layout>
            <Container>

                <ProductDetail product={product} /> {/* Kirimkan produk spesifik */}
                <ProductCategory title="Category A" products={products.slice(0, 5)} />
                <ProductCategory title="Category B" products={products.slice(6, 15)} />
            </Container>

        </Layout>
    );

}