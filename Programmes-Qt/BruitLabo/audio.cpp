#include "audio.h"

Audio::Audio(QObject *parent, bool debug):
    QObject(parent)
    ,m_Inputdevice(QAudioDeviceInfo::defaultInputDevice())
    ,m_audioInput(0)
    ,m_input(0)
    ,m_buffer(14096, 0)
    ,m_debug(debug){

    initializeAudio();
}

void Audio::initializeAudio(){

    m_format.setSampleRate(8000);//set frequency to 8000
    m_format.setChannelCount(1);//set channels to mono
    m_format.setSampleSize(16); //set sample sze to 16 bit
    m_format.setSampleType(QAudioFormat::UnSignedInt); //Sample type as usigned integer sample
    m_format.setByteOrder(QAudioFormat::LittleEndian); //Byte order
    m_format.setCodec("audio/pcm"); //set codec as simple audio/pcm

    QAudioDeviceInfo infoIn(QAudioDeviceInfo::defaultInputDevice());
    if (!infoIn.isFormatSupported(m_format)){
        qDebug() << "Default format not supported, trying to use the nearest.";
        m_format = infoIn.nearestFormat(m_format);
    }

    createAudioInput();

    //Audio input device
    m_input = m_audioInput->start();
    //Call readmore when audio samples fill in inputbuffer
    connect(m_input, SIGNAL(readyRead()), this, SLOT(readMore()));

}

void Audio::createAudioInput(){
    if (m_input != 0) {
        disconnect(m_input, 0, this, 0);
        m_input = 0;
    }
    m_audioInput = new QAudioInput(m_Inputdevice, m_format, this);
}


void Audio::readMore(){
    //Return if audio input is null
    if(!m_audioInput) return;

    //Check the number of samples in input buffer
    qint64 len = m_audioInput->bytesReady();

    //Limit sample size
    if(len > 4096) len = 4096;
    //Read sound samples from input device to buffer
    qint64 l = m_input->read(m_buffer.data(), len);
    if(l > 0){
        //Assign sound samples to short array
        short* resultingData = (short*)m_buffer.data();

        double rms = 0;
        double mean = 0;
        for (int i=1; i < len; i++) mean += resultingData[i];
        mean = mean/double(len);
        for (int i=1; i < len; i++) rms += pow(resultingData[i]-mean,2);
        rms = sqrt(rms/len);

        emit db_level( 20*log10(pow(2,16)) + 20*log10(rms/32767.0) );

    }

}
