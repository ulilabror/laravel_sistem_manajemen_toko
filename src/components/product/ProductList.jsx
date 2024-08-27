import ProductCard from "../common/ProductCard";
export default function ProductList({ products }) {
    return (
        <div className=" bg-gray-100 dark:bg-gray-800 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-4">
            {products.map((product) => (
                <ProductCard key={product.product_barcode_id} product={product} />
            ))}
        </div>
    );
}
