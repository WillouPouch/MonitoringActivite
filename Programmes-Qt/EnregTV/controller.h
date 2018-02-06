#ifndef CONTROLLER_H
#define CONTROLLER_H

#include <QtCore>

class Controller: public QObject {
    Q_OBJECT

public:
    explicit Controller(unsigned int tps_ms_tv_on_off, QObject *parent = Q_NULLPTR, bool debug = false);

private:
    unsigned int m_tps_ms_tv_on_off;
    QTimer *m_timer_in;
    QTimer *m_timer_out;
    QDateTime m_start_date;
    QDateTime m_end_date;
    bool m_cycle;
    bool m_debug;

public slots:
        void tv_on();
        void tv_off();

private slots:
        void cycle_in();
        void cycle_out();

signals:
    void send_data(QString);
};

#endif // CONTROLLER_H
