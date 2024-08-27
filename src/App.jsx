import "./index.css";
import React, { Suspense } from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import products from "./data/productsData";

const MainPage = React.lazy(() => import("./pages/MainPage"));
const LoginPage = React.lazy(() => import("./pages/auth/LoginPage"));
const RegisterPage = React.lazy(() => import("./pages/auth/RegisterPage"))
const ProductDetail = React.lazy(() => import("./pages/product/ProductDetailPage"))
const ProductList = React.lazy(() => import("./pages/product/ProductListpage"))

export default function App() {
  return (
    <Router>
      <div className="App">
        <Suspense fallback={<div>Loading...</div>}>
          <Routes>
            <Route path="/" element={<MainPage />} />
            <Route path="/login" element={<LoginPage />} />
            <Route path="/register" element={<RegisterPage />} />
            <Route path="/products" element={<ProductList products={products} />} />
            <Route path="/product/:id" element={<ProductDetail products={products} />} />
          </Routes>
        </Suspense>
      </div>
    </Router>
  );
}
