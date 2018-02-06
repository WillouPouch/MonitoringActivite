#ifndef LISTECONFANOMALIESONORE_H
#define LISTECONFANOMALIESONORE_H

#include <QtCore>
#include <confanomaliesonore.h>
#include "network.h"

class ListeConfAnomalieSonore : public QObject {
    Q_OBJECT

public:
    explicit ListeConfAnomalieSonore(QObject *parent = Q_NULLPTR, bool debug = false);
    const ConfAnomalieSonore* get_cas_time(QTime);

public slots :
    void timer_update();
    void reload_vector_from_json(QString);

private:
    QTimer *m_timer;
    Network m_network;
    QVector<ConfAnomalieSonore> m_vector;
    bool m_debug;

};

#endif //LISTECONFANOMALIESONORE_H
