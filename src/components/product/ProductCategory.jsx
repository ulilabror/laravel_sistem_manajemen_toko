import React from 'react';
import ProductScrollHorizontalList from './ProductScrollHorizontalList';
import ProductCard from '../common/ProductCard';

export default function ProductCategory({ title, products }) {
    return (
        <div className="container mx-auto px-4 my-6">
            <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-4">{title}</h2>
            <ProductScrollHorizontalList>
                {products.map((product) => (
                    <ProductCard key={product.product_barcode_id} product={product} />
                ))}
            </ProductScrollHorizontalList>
        </div>
    );
}
