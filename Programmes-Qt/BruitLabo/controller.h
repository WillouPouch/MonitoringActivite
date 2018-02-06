#ifndef CONTROLLER_H
#define CONTROLLER_H

#include <QtCore>
#include "confanomaliesonore.h"
#include "listeconfanomaliesonore.h"

class Controller: public QObject {
    Q_OBJECT

public:
    explicit Controller(QObject *parent = Q_NULLPTR, bool debug = false);

private:
    QElapsedTimer m_timer_anomalie;
    QElapsedTimer m_timer_decrease;
    QVector<double> m_tab_db;
    bool m_debug;
    bool m_anomalie;
    unsigned int m_duree;
    unsigned int m_seuil;
    QDateTime m_start_date;
    ListeConfAnomalieSonore *m_liste_cas;

public slots:
        void db_level(double);

signals:
    void send_data(QString);
};

#endif // CONTROLLER_H
