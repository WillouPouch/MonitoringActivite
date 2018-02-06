#ifndef CONTROLLER_H
#define CONTROLLER_H

#include <QtCore>

class Controller: public QObject {
    Q_OBJECT

public:
    explicit Controller(QObject *parent = Q_NULLPTR, bool debug = false);

private:
    QDateTime m_start_date;
    QString m_zone;
    bool m_debug;

public slots:
        void zone_changed(QString);

signals:
    void send_data(QString);
};

#endif // CONTROLLER_H
