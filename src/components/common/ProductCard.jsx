import { Link } from 'react-router-dom';

export default function ProductCard({ product }) {
    return (
        <div className="bg-white dark:bg-gray-900 rounded-lg shadow-md p-4 w-64 flex-shrink-0"> {/* Fixed width */}
            {product.files.length > 0 && (
                <img
                    src={product.files[0]}
                    alt={product.product_name}
                    className="w-full h-48 object-cover rounded-md"
                />
            )}
            <h3 className="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                {product.product_name}
            </h3>
            <p className="mt-1 text-gray-700 dark:text-gray-300">SKU: {product.product_sku}</p>
            <p className="mt-1 text-gray-700 dark:text-gray-300">Rp.{product.product_price}</p>
            <Link
                to={`/product/${product.product_barcode_id}`}
                className="mt-4 block text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
            >
                View Details
            </Link>
        </div>
    );
}
