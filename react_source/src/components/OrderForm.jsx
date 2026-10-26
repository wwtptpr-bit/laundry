import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { toast } from '@/components/ui/use-toast';

const OrderForm = () => {
  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    address: '',
    service: 'reguler',
    weight: '',
    notes: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    
    if (!formData.name || !formData.phone || !formData.address || !formData.weight) {
      toast({
        title: '⚠️ Form Belum Lengkap',
        description: 'Mohon lengkapi semua field yang wajib diisi',
        variant: 'destructive',
      });
      return;
    }

    const orders = JSON.parse(localStorage.getItem('laundryOrders') || '[]');
    const newOrder = {
      id: Date.now(),
      ...formData,
      date: new Date().toISOString(),
      status: 'pending',
    };
    orders.push(newOrder);
    localStorage.setItem('laundryOrders', JSON.stringify(orders));

    toast({
      title: '✅ Pesanan Diterima!',
      description: 'Terima kasih! Tim kami akan segera menjemput laundry Anda.',
    });

    setFormData({
      name: '',
      phone: '',
      address: '',
      service: 'reguler',
      weight: '',
      notes: '',
    });
  };

  return (
    <section id="order" className="py-20 px-4 bg-gradient-to-b from-white to-green-50">
      <div className="container mx-auto max-w-3xl">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-12"
        >
          <h2 className="text-4xl md:text-5xl font-bold mb-4">
            Pesan Antar <span className="bg-gradient-to-r from-green-600 to-emerald-500 bg-clip-text text-transparent">Jemput</span>
          </h2>
          <p className="text-xl text-gray-600">
            Isi form di bawah ini untuk layanan antar jemput gratis!
          </p>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="bg-white rounded-3xl p-8 md:p-12 shadow-2xl border border-green-100"
        >
          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <Label htmlFor="name" className="text-gray-700 font-semibold">Nama Lengkap *</Label>
              <Input
                id="name"
                type="text"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                className="mt-2 border-2 border-green-100 focus:border-green-500"
                placeholder="Masukkan nama lengkap Anda"
              />
            </div>

            <div>
              <Label htmlFor="phone" className="text-gray-700 font-semibold">Nomor WhatsApp *</Label>
              <Input
                id="phone"
                type="tel"
                value={formData.phone}
                onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                className="mt-2 border-2 border-green-100 focus:border-green-500"
                placeholder="Contoh: 081234567890"
              />
            </div>

            <div>
              <Label htmlFor="address" className="text-gray-700 font-semibold">Alamat Penjemputan *</Label>
              <Textarea
                id="address"
                value={formData.address}
                onChange={(e) => setFormData({ ...formData, address: e.target.value })}
                className="mt-2 border-2 border-green-100 focus:border-green-500"
                placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan"
                rows={3}
              />
            </div>

            <div>
              <Label htmlFor="service" className="text-gray-700 font-semibold">Pilih Paket *</Label>
              <select
                id="service"
                value={formData.service}
                onChange={(e) => setFormData({ ...formData, service: e.target.value })}
                className="mt-2 w-full px-4 py-2 border-2 border-green-100 rounded-md focus:border-green-500 focus:outline-none bg-white"
              >
                <option value="reguler">Reguler (Rp 6.000/kg)</option>
                <option value="express">Express 24 Jam (Rp 9.000/kg)</option>
                <option value="kilat">Kilat 6 Jam (Rp 15.000/kg)</option>
              </select>
            </div>

            <div>
              <Label htmlFor="weight" className="text-gray-700 font-semibold">Estimasi Berat (kg) *</Label>
              <Input
                id="weight"
                type="number"
                value={formData.weight}
                onChange={(e) => setFormData({ ...formData, weight: e.target.value })}
                className="mt-2 border-2 border-green-100 focus:border-green-500"
                placeholder="Contoh: 5"
                min="1"
              />
            </div>

            <div>
              <Label htmlFor="notes" className="text-gray-700 font-semibold">Catatan Tambahan</Label>
              <Textarea
                id="notes"
                value={formData.notes}
                onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                className="mt-2 border-2 border-green-100 focus:border-green-500"
                placeholder="Contoh: Ada pakaian luntur, jemput setelah jam 5 sore"
                rows={3}
              />
            </div>

            <Button
              type="submit"
              className="w-full bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 text-lg py-6"
            >
              Kirim Pesanan Jemput
            </Button>
          </form>
        </motion.div>
      </div>
    </section>
  );
};

export default OrderForm;