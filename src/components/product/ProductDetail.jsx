import { useParams } from 'react-router-dom';
// Core modules imports are same as usual
import { Pagination, Scrollbar, Navigation, A11y } from 'swiper/modules';
// Direct React component imports
import { Swiper, SwiperSlide } from 'swiper/react';


// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';

export default function ProductDetail({ product }) {

    if (!product) {
        return (<>
            <section className="flex items-center justify-center h-screen bg-gray-100 dark:bg-gray-800">
                <div className="text-center text-gray-700 dark:text-gray-300">
                    <h2 className="text-2xl font-bold mb-4">Product not found</h2>
                    <p className="text-lg">We couldn't find the product you're looking for.</p>
                </div>
            </section>

        </>
        );
    }

    return (
        <div className="max-w-3xl mx-auto p-4">
            {product.files.length > 1 ? (
                <Swiper
                    modules={[Navigation, Pagination, Scrollbar, A11y]}
                    spaceBetween={10}
                    slidesPerView={1}
                    pagination={{ clickable: true }}
                    navigation
                    scrollbar={{ draggable: true }}
                    className="w-full h-64"
                >
                    {product.files.map((image, index) => (
                        <SwiperSlide key={index}>
                            <img
                                src={image}
                                alt={`${product.product_name} ${index + 1}`}
                                className="w-full h-64 object-cover rounded-lg"
                            />
                        </SwiperSlide>
                    ))}
                </Swiper>
            ) : (
                <img
                    src={product.files[0]}
                    alt={product.product_name}
                    className="w-full h-64 object-cover rounded-lg"
                />
            )}

            <h1 className="mt-4 text-3xl font-bold text-gray-900 dark:text-white">{product.product_name}</h1>
            <p className="mt-2 text-xl text-gray-700 dark:text-gray-300">${product.product_price}</p>
            <p className="mt-4 text-gray-600 dark:text-gray-400">{product.product_description}</p>
            <p className="mt-4 text-sm text-gray-500 dark:text-gray-400">Created by: {product.created_by}</p>
            <p className="mt-2 text-sm text-gray-500 dark:text-gray-400">Created at: {new Date(product.created_at).toLocaleDateString()}</p>
            <p className="mt-2 text-sm text-gray-500 dark:text-gray-400">Updated at: {new Date(product.updated_at).toLocaleDateString()}</p>
        </div>
    );
}
