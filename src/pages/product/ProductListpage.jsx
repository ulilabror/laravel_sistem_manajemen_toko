import Layout from "../../components/layouts/Layout";
import ProductList from "../../components/product/ProductList";


export default function ProductListPage({ products }) {
    return (
        <>
            <Layout>

                <ProductList products={products} />

            </Layout>
        </>
    )
}