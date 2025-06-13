{{/*
Expand the name of the chart.
*/}}
{{- define "drupal-k8s.name" -}}
{{- default .Chart.Name .Values.nameOverride | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Create a default fully qualified app name.
We truncate at 63 chars because some Kubernetes name fields are limited to this (by the DNS naming spec).
If release name contains chart name it will be used as a full name.
*/}}
{{- define "drupal-k8s.fullname" -}}
{{- if .Values.fullnameOverride }}
{{- .Values.fullnameOverride | trunc 63 | trimSuffix "-" }}
{{- else }}
{{- $name := default .Chart.Name .Values.nameOverride }}
{{- if contains $name .Release.Name }}
{{- .Release.Name | trunc 63 | trimSuffix "-" }}
{{- else }}
{{- printf "%s-%s" .Release.Name $name | trunc 63 | trimSuffix "-" }}
{{- end }}
{{- end }}
{{- end }}

{{/*
Create chart name and version as used by the chart label.
*/}}
{{- define "drupal-k8s.chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" }}
{{- end }}

{{/*
Common labels
*/}}
{{- define "drupal-k8s.labels" -}}
helm.sh/chart: {{ include "drupal-k8s.chart" . }}
{{ include "drupal-k8s.selectorLabels" . }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end }}

{{/*
Selector labels
*/}}
{{- define "drupal-k8s.selectorLabels" -}}
app.kubernetes.io/name: {{ include "drupal-k8s.name" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
{{- end }}

{{/*
Create the name of the service account to use
*/}}
{{- define "drupal-k8s.serviceAccountName" -}}
{{- if .Values.serviceAccount.create }}
{{- default (include "drupal-k8s.fullname" .) .Values.serviceAccount.name }}
{{- else }}
{{- default "default" .Values.serviceAccount.name }}
{{- end }}
{{- end }}

{{- define "drupal-k8s.containerEnvs" -}}
- name: DRUSH_OPTIONS_URI
  value: {{ .Values.drupal.url }}
- name: DRUPAL_K8S_ENVIRONMENT
  value: {{ .Values.drupal.environment }}

{{- if .Values.memcached.enabled }}
- name: MEMCACHE_SERVER
  value: "localhost:11211"
{{- end }}
{{- end }}

{{- define "drupal-k8s.probe" -}}
{{- if eq .Values.probe.type "tcp" }}
tcpSocket:
  port: 8080
{{- else }}
httpGet:
    path: {{ .Values.probe.path }}
    port: 8080
    httpHeaders:
      - name: Host
        value: {{ .Values.drupal.url }}
    {{- if .Values.shield.enabled }}
      - name: Authorization
        value: Basic {{ (printf "%s:%s" .Values.shield.username .Values.shield.password) | b64enc }}
    {{- end }}
{{- end }}

{{- end }}

{{- define "drupal-k8s.drupal-pvc-name" -}}
{{- if .Values.drupal.pvc.enabled }}
{{- if .Values.drupal.pvc.claimName }}
{{ .Values.drupal.pvc.claimName }}
{{- else }}
{{- include "drupal-k8s.fullname" . }}-drupal-pvc
{{- end }}
{{- end }}
{{- end }}
