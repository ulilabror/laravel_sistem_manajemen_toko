export default function Section({ children }) {
    return (
        <section className="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-800">
            {children}
        </section>
    )
}