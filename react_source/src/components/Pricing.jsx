import React from 'react';
import { motion } from 'framer-motion';
import { Check } from 'lucide-react';
import { Button } from '@/components/ui/button';

const Title = ({ children }) => (
  <motion.h2 
    initial={{ opacity: 0, y: 20 }}
    whileInView={{ opacity: 1, y: 0 }}
    viewport={{ once: true, amount: 0.5 }}
    transition={{ duration: 0.5 }}
    className="text-4xl md:text-5xl font-serif font-bold mb-4"
  >
    {children}
  </motion.h2>
);

const Pricing = () => {
  const packages = [
    {
      name: 'Reguler',
      price: '6.000',
      unit: '/kg',
      features: ['Selesai 2-3 hari', 'Cuci bersih & wangi', 'Setrika rapi', 'Gratis antar jemput*'],
      popular: false,
      bg: 'from-green-50 to-green-100',
    },
    {
      name: 'Express',
      price: '9.000',
      unit: '/kg',
      features: ['Selesai 1 hari', 'Cuci bersih & wangi', 'Setrika rapi', 'Gratis antar jemput*', 'Prioritas layanan'],
      popular: true,
      bg: 'from-emerald-50 to-emerald-100',
    },
    {
      name: 'Kilat',
      price: '15.000',
      unit: '/kg',
      features: ['Selesai 6 jam', 'Cuci super cepat', 'Setrika kilat', 'Gratis antar jemput*', 'Prioritas utama', 'Parfum premium'],
      popular: false,
      bg: 'from-teal-50 to-teal-100',
    },
  ];

  const scrollToOrder = () => {
    const element = document.getElementById('contact');
    if (element) {
      element.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  };

  return (
    <section id="pricing" className="py-20 px-4 section-bg water-effect" style={{'--ripple-delay': '4s'}}>
      <div className="container mx-auto">
        <div className="text-center mb-16">
          <Title>
            Harga <span className="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Bersahabat</span>
          </Title>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.5 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-xl text-muted-foreground max-w-2xl mx-auto"
          >
            Pilih paket yang paling sesuai dengan kecepatan dan kebutuhan Anda.
          </motion.p>
        </div>

        <div className="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
          {packages.map((pkg, index) => (
            <motion.div 
              key={index} 
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ y: -10 }}
              className={`relative bg-card rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 border-2 ${pkg.popular ? 'border-primary' : 'border-transparent'}`}
            >
              <div className={`absolute inset-0 rounded-3xl opacity-50 bg-gradient-to-br ${pkg.bg} -z-10`}></div>
              {pkg.popular && (
                <div className="absolute -top-4 left-1/2 transform -translate-x-1/2">
                  <span className="bg-gradient-to-r from-primary to-emerald-500 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg">
                    PALING LARIS
                  </span>
                </div>
              )}

              <div className="text-center mb-8">
                <h3 className="text-2xl font-serif font-bold mb-4 text-foreground">{pkg.name}</h3>
                <div className="flex items-end justify-center gap-1">
                  <span className="text-muted-foreground text-lg">Rp</span>
                  <span className={`text-5xl font-bold bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent`}>
                    {pkg.price}
                  </span>
                  <span className="text-muted-foreground text-lg mb-2">{pkg.unit}</span>
                </div>
              </div>

              <ul className="space-y-4 mb-8">
                {pkg.features.map((feature, idx) => (
                  <li key={idx} className="flex items-start gap-3">
                    <div className={`w-6 h-6 bg-gradient-to-br from-primary to-emerald-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5`}>
                      <Check className="w-4 h-4 text-white" />
                    </div>
                    <span className="text-foreground">{feature}</span>
                  </li>
                ))}
              </ul>

              <Button onClick={scrollToOrder} className={`w-full ${pkg.popular ? 'bg-gradient-to-r from-primary to-emerald-500 hover:opacity-90' : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'}`}>
                Pilih Paket
              </Button>
            </motion.div>
          ))}
        </div>

        <p className="text-center text-muted-foreground mt-8 text-sm">*Gratis antar jemput untuk minimal 5kg dalam radius 3km.</p>
      </div>
    </section>
  );
};
export default Pricing;