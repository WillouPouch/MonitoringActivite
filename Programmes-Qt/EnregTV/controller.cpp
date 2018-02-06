#include "controller.h"

Controller::Controller(unsigned int tps_ms_tv_on_off, QObject *parent, bool debug) :
    QObject(parent)
    ,m_tps_ms_tv_on_off(tps_ms_tv_on_off)
    ,m_cycle(false)
    ,m_debug(debug){

    m_timer_in = new QTimer(this);
    m_timer_out = new QTimer(this);
    connect(m_timer_in, SIGNAL(timeout()), this, SLOT(cycle_in()));
    connect(m_timer_out, SIGNAL(timeout()), this, SLOT(cycle_out()));

}

/*
L'objectif est d'assurer l'inactivité de la tv ou le début
d'une activité tv pour au moins "m_tps_ms_tv_on_off" secondes afin de considérer
une activité tv commencée ou terminée (suppression des parasites)
*/
void Controller::tv_on(){
    m_timer_out->stop();
    m_timer_in->start(m_tps_ms_tv_on_off);
    if(!m_cycle) m_start_date = QDateTime::currentDateTime();
}

void Controller::tv_off(){
    m_timer_in->stop();
    m_timer_out->start(m_tps_ms_tv_on_off);
    m_end_date = QDateTime::currentDateTime();
    if(m_cycle) m_end_date = QDateTime::currentDateTime();
}

void Controller::cycle_in(){
    m_timer_in->stop();
    m_cycle = true;
}

void Controller::cycle_out(){
    m_timer_out->stop();

    //Fin d'un cycle de visionnage tv
    if(m_cycle){
        QJsonObject obj;
        obj.insert("date_debut", m_start_date.toString(QString("dd/MM/yyyy HH:mm:ss")));
        obj.insert("date_fin", m_end_date.toString(QString("dd/MM/yyyy HH:mm:ss")));
        obj.insert("type_activite", "tv_on");
        QJsonDocument doc(obj);
        if(m_debug) qDebug() << "[Controller::cycle_out] doc : " << doc.toJson(QJsonDocument::Compact);
        emit send_data(doc.toJson(QJsonDocument::Compact));
    }
    m_cycle = false;
}

